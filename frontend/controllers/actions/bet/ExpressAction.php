<?php
namespace frontend\controllers\actions\bet;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use common\components\NException;
use common\factories\DataToViewFactory;
use common\factories\TransferFactory;
use common\helpers\Convert;
use BettingGroupExpress;
use CDbCriteria;
use CDbCacheDependency;
use common\singletons\prices\Prices;
use Yii;

class ExpressAction extends CAction
{
    public $admin_part = false;

    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
    	if(!Yii::app()->getUser()->isAdmin()) {
			Yii::app()->getAjax()->addErrors("Ставки временно приостановлены")
				->send();
			die;
		}

        $data = \Yii::app()->getRequest()->getPost('data', ['items' => [], 'price' => 0]);
        if(empty($data['items']))
            Yii::app()->getAjax()->addErrors('Данные не получены')->send();

        $event_list = $data['items'];
        $ids = array_keys($event_list);
        $sum = Convert::getMoneyFormat($data['price']);

        if ($sum <= 0.00)
            \Yii::app()->getAjax()->addErrors("Некорректная ставка. Вы что-то делаете не так. Вы указали сумму {$sum}")->send();
        if ($sum > \Yii::app()->getUser()->getActiveBalanceCount())
            \Yii::app()->getAjax()->addErrors("Недостаточно средств на активном счете")->send();

        $min_bet = Prices::init()->getMinBet();
        if ($min_bet > $sum)
            Yii::app()->getAjax()->addErrors(sprintf('Минимальная сумма ставки %s', $min_bet))->send();

        $t = \Yii::app()->getDb()->beginTransaction();
        try {
            /** @var \User $User */
            $User = \User::model()->findByPk(\Yii::app()->getUser()->getId());

            $criteria = new \CDbCriteria();
            $criteria->addInCondition('`t`.id', $ids);
            $criteria->addCondition('`t`.date_int > :date_int');
            $criteria->scopes = ['for_user'];
            $criteria->with = ['sport'];
            $criteria->index = 'id';
            $criteria->params = \CMap::mergeArray($criteria->params, [':date_int' => time()]);

            $dependency = new CDbCacheDependency('SELECT MAX(update_at) FROM {{sport_event}}');
            $dependency->reuseDependentData = true;

            /** @var \iSportEvent[] $models */
            $models = \SportEvent::model()->cache(3600, $dependency)->findAll($criteria);
            if(count($models) != count($ids)) {
                $remove_ids = [];
                foreach ($ids as $id) {
                    if(!isset($models[$id])) {
                        $title = $event_list[$id]['title'];
                        Yii::app()->getAjax()
                            ->addErrors(sprintf('Событие %d: %s не было найдено', $id, $title));
                        $remove_ids[] = $id;
                    }
                }
                Yii::app()->getAjax()->addOther(['remove' => $remove_ids]);
                throw new \Exception();
            }

            if(count($models) < 2) {
                Yii::app()->getAjax()->addErrors('Эксперсс не может состоять меньше чем из 2 событий');
                throw new \Exception();
            }

            $criteria = new \CDbCriteria();
            $criteria->select = ['event_id', 'type', 'value'];
            $criteria->addInCondition('`t`.event_id', array_keys($models));
            $criteria->addCondition('`t`.position = :position');
            $criteria->params[':position'] = \SportEventRatio::POSITION_NEW;
            $new_ratio = \SportEventRatio::model()
                ->getCommandBuilder()
                ->createFindCommand(\SportEventRatio::model()->tableName(), $criteria)
                ->queryAll();
            $new_ratio_list = [];
            foreach ($new_ratio as $ratio)
                $new_ratio_list[$ratio['event_id']][$ratio['type']] = $ratio['value'];

            $criteria->params[':position'] = \SportEventRatio::POSITION_LAST;
            $last_ratio = \SportEventRatio::model()
                ->getCommandBuilder()
                ->createFindCommand(\SportEventRatio::model()->tableName(), $criteria)
                ->queryAll();
            $last_ratio_list = [];
            foreach ($last_ratio as $ratio)
                $last_ratio_list[$ratio['event_id']][$ratio['type']] = $ratio['value'];

            foreach (array_keys($models) as $key) {
                $models[$key]->scenario = 'betting';
                if (isset($new_ratio_list[$key]))
                    $models[$key]->getNewRatio()->populateRecord($new_ratio_list[$key], true);
                if (isset($last_ratio_list[$key]))
                    $models[$key]->getOldRatio()->populateRecord($last_ratio_list[$key], true);
            }

            $BettingGroup = new BettingGroupExpress();

            $criteria = new CDbCriteria();
            $criteria->scopes = ['own'];
            $count = (int)\BettingGroup::model()->count($criteria) + 1;
            $BettingGroup->setUserGroupNumber($count);

            $ratio = 1;
            $last_event_id = null;
            $last_datetime = null;
            $max_bet_list = [];
            $max_bet = null;
            foreach ($event_list as $id => $value) {
                $model = $models[$id];

                $DataToView = DataToViewFactory::factory($model->getSportType(), ['Event' => $model]);

                $ratio_field = $model->getFieldByAlias($value['ratio_type']);
                $ratio_value = $model->getNewRatio()->getAttribute($ratio_field);
                if ($ratio_value != $value['ratio_value']) {
                    Yii::app()->getAjax()
                        ->addOther(['update' => [['id' => $id, 'value' => $ratio_value]]])
                        ->addErrors("Изменился коэфициент на событие {$DataToView->buildMessageResultString($value['type'])}. Новый: {$ratio_value}");

                    throw new \Exception();
                }

                $ratio *= $ratio_value;
                $max_bet_list[$id] = \Yii::app()->getSport()->getMaxBet($model, $ratio_value);

                $max_bet = ($max_bet === null || $max_bet > $max_bet_list[$id]) ? $max_bet_list[$id] : $max_bet;
            }
            $max_bet = $max_bet === null ? 0 : $max_bet;
            $ratio = Convert::getMoneyFormat($ratio);
            if ($ratio > Prices::init()->getMaxRatio()) {
                Yii::app()->getAjax()->addErrors('Максимальный коэффициент для экспресс ставки - ' . Prices::init()->getMaxRatio());
                throw new \Exception();
            }

            if ($sum > $max_bet) {
                \Yii::app()->getAjax()->addErrors("Максимальная сумма ставки для этого экспресса {$max_bet}");
                throw new \Exception();
            }

            $BettingGroup->setPrice($sum)
                ->setPriceType(\Yii::app()->getUser()->getAB())
                ->setRatioValue($ratio);
            if (!$BettingGroup->createAction()) {
                \Yii::app()->getAjax()->addErrors($BettingGroup);
                throw new NException("Не удалось создать экспресс", NException::ERROR_BET_EXPRESS,
                    ['errors' => $BettingGroup->getErrors(), 'attr' => $BettingGroup->getAttributes()]);
            }

            $Betting = TransferFactory::factory('betting', Yii::app()->getUser()->getAB(), [$User, $BettingGroup]);
            if (!$Betting->run($sum)) {
                \Yii::app()->getAjax()->addErrors($Betting->getErrorMsg());
                throw new NException();
            }

            foreach ($event_list as $id => $item) {
                /** @var \iSportEvent $event */
                $event = $models[$id];
                $ratio_field = $event->getFieldByAlias($item['ratio_type']);
                $ratio_value = $event->getNewRatio()->getAttribute($ratio_field);

                $Bet = new \UserBetting();
                $Bet
                    ->setStatus(\UserBetting::STATUS_NEW)
                    ->setEventId($event->getId())
                    ->setPrice($sum)
                    ->setPriceType(\Yii::app()->getUser()->getAB())
                    ->setBetGroupId($BettingGroup->getId())
                    ->setRatioType($ratio_field)
                    ->setRatioValue($ratio_value)
                    ->setDopRatio(Prices::init()->getDopRatio())
                    ->setDateInt($event->getDateInt())
                    ->setV($event->getV());

                if (!$Bet->createAction()) {
                    \Yii::app()->getAjax()->addErrors($Bet);
                    throw new NException("Неудалось добавить ставку в историю", NException::ERROR_BET_EXPRESS,
                        ['errors' => $Bet->getErrors(), 'attr' => $Bet->getAttributes()]);
                }
            }

            $t->commit();
            $User->sendBalanceToFront();

            \Yii::app()->getAjax()
                ->addMessage("Ставка принята. {$ratio} x {$BettingGroup->getPrice()}")
                ->addOther(['bet' => 'success']);

        } catch (\Exception $ex) {
            $t->rollback();
            \MException::logMongo($ex, 'bet');
        }

        \Yii::app()->getAjax()->menu()->send();
    }
}