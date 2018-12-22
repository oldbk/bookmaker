<?php
namespace frontend\modules\admin\controllers\actions\event;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use common\factories\DataToViewFactory;
use Yii;
use CDbCriteria;

class DeleteRatioAction extends CAction
{
    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        $event = Yii::app()->getRequest()->getParam('event_id');
        $field = Yii::app()->getRequest()->getParam('ratio');

        $t = Yii::app()->getDb()->beginTransaction();
        try {
            /** @var \iSportEvent $Event */
            $Event = \SportEvent::model()->with('sport')->findByPk($event);
            if (!$Event) {
                Yii::app()->getAjax()->addErrors('Событие не найдено');
                throw new \Exception();
            }

            $DataToView = DataToViewFactory::factory($Event->getSportType(), ['Event' => $Event]);

            $message = sprintf("Удалил фиксацию для коэффициента %s со значением %s", $DataToView->getResultLabel($field), $Event->getAttribute($field));
            $Event->onAfterEventRatioChange = [new \AdminHistory($message), 'afterRatioChange'];
            if (!$Event->updateAction()) {
                Yii::app()->getAjax()->addErrors($Event);
                throw new \Exception();
            }

            $criteria = new CDbCriteria();
            $criteria->addCondition('event_id = :event_id');
            $criteria->addCondition('ratio_name = :ratio_name');
            $criteria->params = [':event_id' => $Event->getId(), ':ratio_name' => $field];
            $FixedRatio = \SportEventFixedValue::model()->find($criteria);
            if ($FixedRatio)
                $FixedRatio->delete();

            $t->commit();
            $link = Yii::app()->getUser()->getState('return_link', Yii::app()->createUrl('/line/event', ['event_id' => $Event->getId()]));
            Yii::app()->getAjax()
                ->addMessage('Фиксация коэффициента удалена')
                ->runJS('updatePage', $link);
        } catch (\Exception $ex) {
            $t->rollback();
            \MException::logMongo($ex);
        }

        Yii::app()->getAjax()->send();
    }
}