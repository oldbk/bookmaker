<?php
namespace frontend\modules\admin\controllers\actions\event;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use common\components\NException;
use Yii;
use common\interfaces\iStatus;

class RefundAction extends CAction
{
    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        $bet_id = Yii::app()->getRequest()->getParam('bet_id');

        $t = Yii::app()->getDb()->beginTransaction();
        try {
            $criteria = new \CDbCriteria();
            $criteria->addCondition('`t`.id = :id');
            $criteria->addCondition('`t`.status = :new');
            $criteria->with = ['userBetting'];
            $criteria->params = [':id' => $bet_id, ':new' => iStatus::STATUS_NEW];
            /** @var \BettingGroup $BettingGroup */
            $BettingGroup = \BettingGroup::model()->find($criteria);
            if (!$BettingGroup) {
                Yii::app()->getAjax()->addErrors('Ставка не найдена');
                throw new \Exception();
            }

            $event = Yii::app()->getRequest()->getParam('event_id');
            $criteria = new \CDbCriteria();
            $criteria->addCondition('id = :id');
            $criteria->params = [':id' => $event];
            /** @var \SportEvent $Event */
            $Event = \SportEvent::model()->find($criteria);
            if (!$Event) {
                Yii::app()->getAjax()->addErrors('Событие не найдено');
                throw new \Exception();
            }

            $r = $BettingGroup->setResultStatus(iStatus::RESULT_RETURN)
                ->setStatus(iStatus::STATUS_HAVE_RESULT)
                ->save();
            if (!$r)
                throw new NException("Не удалось обновить группу после зачислений", NException::ERROR_BET, [
                    'attributes' => $BettingGroup->getAttributes(),
                    'class' => 'frontend\modules\admin\controllers\actions\event\RefundAction',
                    'method' => 'run'
                ]);

            \UserBetting::model()->updateAll(
                [
                    'status' => iStatus::STATUS_FINISH,
                    'result_status' => iStatus::RESULT_RETURN,
                    'ratio_value' => 1.00,
                    'update_at' => time()
                ],
                'bet_group_id = :bet_group_id',
                [':bet_group_id' => $BettingGroup->getId()]
            );

            $t->commit();
        } catch (\Exception $ex) {
            $t->rollback();
            \MException::logMongo($ex);
        }

        Yii::app()->getAjax()->send();
    }
}