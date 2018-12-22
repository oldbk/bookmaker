<?php
namespace frontend\modules\user\controllers\actions\bet;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use common\components\NException;
use common\helpers\Convert;
use common\singletons\Settings;
use Yii;
use BettingGroup;
use CDbCriteria;
use common\interfaces\iStatus;

class RefundAction extends CAction
{
    public $admin_part = false;

    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        Yii::app()->getAjax()->addErrors('Данная возможность выключена')->send();

        $criteria = new CDbCriteria();
        $criteria->addCondition('`t`.id = :id');
        $criteria->addCondition('`t`.status = :new');
        $criteria->scopes = ['own'];
        $criteria->with = ['userBetting' => ['with' => ['event']]];
        $criteria->params = [':id' => Yii::app()->getRequest()->getParam('bet_id'), ':new' => iStatus::STATUS_NEW];
        /** @var BettingGroup $BettingGroup */
        $BettingGroup = BettingGroup::model()->find($criteria);

        $minStartAt = 0;
        foreach ($BettingGroup->userBetting as $Bet) {
            $Event = $Bet->event;
            if ($minStartAt === 0)
                $minStartAt = $Event->getDateInt();
            if (time() > strtotime("-" . Settings::init()->getMinTimeDecline() . " minutes", $Event->getDateInt())) {
                Yii::app()->getAjax()->addErrors("Максимальное доступное время для отмены ставки истекло")
                    ->send();
            }

            if ($Event->getDateInt() < $minStartAt)
                $minStartAt = $Event->getDateInt();
        }
        //$maxRefund = strtotime("-" . Settings::init()->getMinTimeDecline() . " minutes", $minStartAt);

        //получаем кол-во 5 минутных интервалов
        //$interval_count = floor(($maxRefund - $BettingGroup->getCreateAt()) / 300);
        //$percent_per_interval = Settings::init()->getMaxPercentDecline() / $interval_count;

        //$have_interval = floor(($maxRefund - time()) / 300);
        //$percent = Convert::getMoneyFormat(($interval_count - $have_interval) * $percent_per_interval);
        $percent = 0;
        //$price_commission = Convert::getMoneyFormat($BettingGroup->getPrice() / 100 * $percent);
        $price_commission = 0;

        $post = Yii::app()->getRequest()->getPost('Refund', []);
        if ($post) {
            if ($post['commission'] != $price_commission || $percent != $post['percent'])
                Yii::app()->getAjax()->addErrors('Время истекло, повторите процедуру')
                    ->send();

            $t = Yii::app()->getDb()->beginTransaction();
            try {
                $return = Convert::getMoneyFormat($BettingGroup->getPrice() - $price_commission);

                $r = $BettingGroup->setStatus(iStatus::STATUS_HAVE_RESULT)
                    ->setResultStatus(iStatus::RESULT_RETURN)
                    ->setPaymentSum($return)
                    ->setRefundAt(time())
                    ->setIsRefund(1)
                    ->save();
                if (!$r) {
                    Yii::app()->getAjax()->addErrors($BettingGroup);
                    throw new NException("Неудачный рефанд", NException::ERROR_USER_REFUND, [
                        'errors' => $BettingGroup->getErrors(),
                        'attributes' => $BettingGroup->getAttributes(),
                        'class' => 'frontend\modules\user\controllers\actions\bet\RefundAction',
                        'method' => 'run'
                    ]);
                }
                \UserBetting::model()->updateAll(
                    [
                        'status' => iStatus::STATUS_FINISH,
                        'result_status' => iStatus::RESULT_RETURN,
                        'update_at' => time()
                    ],
                    'bet_group_id = :bet_group_id',
                    [':bet_group_id' => $BettingGroup->getId()]
                );

                $t->commit();

                Yii::app()->getAjax()->addMessage("Ставка отменена. Вам будут возвращены {$return}")
                    ->runJS('closeModal')->runJS('updatePage', [Yii::app()->createUrl('/user/bet/history')]);
            } catch (\Exception $ex) {
                $t->rollback();
                \MException::logMongo($ex);
            }

            Yii::app()->getAjax()->send();
        }

        $params = [
            'BettingGroup' => $BettingGroup,
            'percent' => $percent,
            'price_commission' => $price_commission,
        ];

        Yii::app()->getAjax()
            ->addReplace('_refund', '#customModal #replacement', $params)
            ->runJS('openCustom')->send();
    }
}