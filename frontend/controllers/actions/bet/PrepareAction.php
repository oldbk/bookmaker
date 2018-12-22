<?php
namespace frontend\controllers\actions\bet;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use common\factories\DataToViewFactory;
use common\helpers\Convert;
use common\singletons\prices\Prices;
use common\singletons\Settings;
use Yii;
use CDbCacheDependency;

class PrepareAction extends CAction
{
    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        $min_level = Settings::init()->getMinLevel();
        $min_bet = Prices::init()->getMinBet();
        if (Yii::app()->getUser()->getLevel() < $min_level)
            Yii::app()->getAjax()->addErrors("Ставки доступны с {$min_level} уровня")->send();

        $ratio_list = Yii::app()->getRequest()->getPost('ratio', []);
        $remove_event = Yii::app()->getRequest()->getPost('remove');
        if ($remove_event && !$ratio_list)
            Yii::app()->getAjax()->addErrors('Вы удалили все события.')->runJS('closeModal')->send();
        $ids = array_keys($ratio_list);

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

        $view_table = [];
        $max_bet = null;
        $express_ratio = 1;
        foreach ($ratio_list as $id => $value) {
            if (!isset($models[$id])) continue;

            $model = $models[$id];

            $DataToView = DataToViewFactory::factory($model->getSportType(), ['Event' => $model]);

            $ratio_field = $model->getFieldByAlias($value['type']);
            $ratio_value = $model->getNewRatio()->getAttribute($ratio_field);
            if ($ratio_value != $value['value'])
                Yii::app()->getAjax()->addErrors("Изменился коэфициент на событие {$DataToView->buildMessageResultString($value['type'])}. Новый: {$ratio_value}");

            $ratio_bet = Yii::app()->getSport()->getMaxBet($model, $ratio_value);
            if ($min_bet > 0 && $min_bet > $ratio_bet)
                $ratio_bet = 0.00;

            $max_bet = ($max_bet === null || $max_bet > $ratio_bet) ? $ratio_bet : $max_bet;
            $view_table[] = [
                'date' => date('d/m H:i', $model->getDateInt()),
                'event' => $model->getTitle(),
                'result' => $DataToView->getResultLabel($ratio_field),
                'ratio' => $ratio_value,
                'event_alias' => $value['type'],
                'event_id' => $model->getId(),
                'max_bet' => $ratio_bet,
                'price_type' => Prices::init()->getShortName(),
            ];

            $express_ratio *= $ratio_value;
        }

        $unique_key = md5(uniqid(rand(), true));
        Yii::app()->getUser()->setState('verify', $unique_key);
        $params = [
            'max_bet' => $max_bet,
            'price_type' => Prices::init()->getShortName(),
            'raws' => $view_table,
            'verify' => $unique_key,
            'link' => count($ratio_list) == 1 ? Yii::app()->createUrl('/bet/ordinar') : Yii::app()->createUrl('/bet/express'),
            'express_ratio' => Convert::getMoneyFormat($express_ratio),
            'max_ratio' => Prices::init()->getMaxRatio()
        ];
        Yii::app()->getAjax()
            ->addReplace('_bet', '#customModal #replacement', $params);
        if (!$remove_event)
            Yii::app()->getAjax()->runJS('openCustom');
    }
}