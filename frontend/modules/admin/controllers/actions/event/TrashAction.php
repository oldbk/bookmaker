<?php
namespace frontend\modules\admin\controllers\actions\event;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use common\helpers\SocketIOHelper;
use common\interfaces\iStatus;
use Yii;
use SportEvent;

class TrashAction extends CAction
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
            $criteria->with = ['sport', 'checkUserBetting'];

            $dependency = new \CDbCacheDependency('SELECT MAX(update_at) FROM {{sport_event}}');
            $dependency->reuseDependentData = true;
            /** @var SportEvent[] $EventList */
            $EventList = SportEvent::model()->cache(3600, $dependency)->findAll($criteria);
            if (!$EventList || count($ids) != count($EventList)) {
                Yii::app()->getAjax()->addErrors('Событие не найдено');
                throw new \Exception();
            }

            $ids = [];
            foreach ($EventList as $Event) {
                if ($Event->checkUserBetting != null && $Event->getStatus() != iStatus::STATUS_FINISH) {
                    Yii::app()->getAjax()->addErrors('Событие не завершено. Закройте событие или верните ставки.');
                    continue;
                }

                $Event->setIsTrash(1)
                    ->onAfterEventTrash = [new \AdminHistory(), 'afterEventTrash'];
                if (!$Event->updateAction()) {
                    Yii::app()->getAjax()->addErrors($Event);
                    throw new \Exception();
                }
                $ids[] = $Event->getId();
            }

            $t->commit();
            SocketIOHelper::eventRemove($ids);

            Yii::app()->getAjax()->addMessage('Событие перенесено в корзину');
        } catch (\Exception $ex) {
            $t->rollback();
            \MException::logMongo($ex);
        }

        Yii::app()->getAjax()->send();
    }
}