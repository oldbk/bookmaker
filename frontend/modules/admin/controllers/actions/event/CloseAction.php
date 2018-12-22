<?php
namespace frontend\modules\admin\controllers\actions\event;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use common\components\NException;
use common\interfaces\iStatus;
use Yii;

class CloseAction extends CAction
{
    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     *
     * @deprecated
     */
    public function run()
    {
        $event = Yii::app()->getRequest()->getParam('event_id');
        $t = Yii::app()->getDb()->beginTransaction();
        try {
            $criteria = new \CDbCriteria();
            $criteria->addCondition('id = :id');
            $criteria->addCondition('status != :status');
            $criteria->params = [':id' => $event, ':status' => iStatus::STATUS_FINISH];
            /** @var \SportEvent $Event */
            $Event = \SportEvent::model()->find($criteria);
            if (!$Event) {
                Yii::app()->getAjax()->addErrors('Событие не найдено');
                throw new \Exception();
            }

            $Event
                ->setStatus(iStatus::STATUS_FINISH)
                ->onAfterEventClose = [new \AdminHistory(), 'afterEventClose'];
            if (!$Event->save()) {
                Yii::app()->getAjax()->addErrors($Event);
                throw new NException("Неудачный рефанд", NException::ERROR_EVENT, [
                    'errors' => $Event->getErrors(),
                    'attributes' => $Event->getAttributes(),
                    'class' => 'frontend\modules\admin\controllers\actions\event\CloseAction',
                    'method' => 'run'
                ]);
            }

            $t->commit();

        } catch (\Exception $ex) {
            $t->rollback();
            \MException::logMongo($ex);
        }

        Yii::app()->getAjax()->send();
    }
}