<?php
namespace frontend\modules\admin\controllers\actions\event;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use common\interfaces\iStatus;
use Yii;

class AcceptAction extends CAction
{
    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        $ids = [Yii::app()->getRequest()->getParam('event_id')];
        $ids = \CMap::mergeArray($ids, Yii::app()->getRequest()->getParam('selected', []));

        $t = Yii::app()->getDb()->beginTransaction();
        try {
            $criteria = new \CDbCriteria();
            $criteria->addInCondition('`t`.id', $ids);
            $criteria->addCondition('`t`.status = :new or `t`.status = :disable');
            $criteria->scopes = ['feature'];
            $criteria->params = \CMap::mergeArray($criteria->params, [
                ':new' => iStatus::STATUS_NEW,
                ':disable' => iStatus::STATUS_DISABLE
            ]);

            /** @var \iSportEvent[] $EventList */
            $EventList = \SportEvent::model()->findAll($criteria);
            if (!$EventList) {
                Yii::app()->getAjax()->addErrors('Событие не найдено');
                throw new \Exception();
            }

            /** @var \iSportEvent[] $event_list */
            $event_list = [];
            foreach ($EventList as $Event) {
                $Event->setStatus(iStatus::STATUS_ENABLE)
                    ->onAfterEventAccept = [new \AdminHistory(), 'afterEventAccept'];
                if (!$Event->updateAction()) {
                    Yii::app()->getAjax()->addErrors($Event);
                    throw new \Exception();
                }

                $event_list[$Event->getId()] = $Event;
            }

            $t->commit();

            Yii::app()->getAjax()->addMessage('Событие активировано и доступно для просмотра и ставок');
            $new_ratio_list = \SportEventRatio::getByIds(array_keys($event_list), \SportEventRatio::POSITION_NEW);
            $last_ratio_list = \SportEventRatio::getByIds(array_keys($event_list), \SportEventRatio::POSITION_LAST);
            foreach ($event_list as $Event) {
                if (isset($new_ratio_list[$Event->getId()]))
                    $Event->getNewRatio()->populateRecord($new_ratio_list[$Event->getId()]);
                if (isset($last_ratio_list[$Event->getId()]))
                    $Event->getOldRatio()->populateRecord($last_ratio_list[$Event->getId()]);

                Yii::app()->getAjax()->addReplace(
                    'ajax/_all',
                    '#content-replacement form tr[data-event="'.$Event->getId().'"]',
                    ['Event' => $Event]
                );
            }
            unset($event_list);

        } catch (\Exception $ex) {
            $t->rollback();
            \MException::logMongo($ex);
        }

        Yii::app()->getAjax()->send();
    }
}