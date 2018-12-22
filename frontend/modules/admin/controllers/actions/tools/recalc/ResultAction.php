<?php
namespace frontend\modules\admin\controllers\actions\tools\recalc;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use common\interfaces\iStatus;
use Yii;

class ResultAction extends CAction
{
    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        $Recalc = Yii::app()->getRequest()->getPost('Recalc', []);
        if(!isset($Recalc['event_id']))
            Yii::app()->getAjax()->addErrors('Вы не ввели ID события')->send();

        $Event = \SportEvent::model()->findByPk($Recalc['event_id']);
        if(!$Event)
            Yii::app()->getAjax()->addErrors('Событие не найдено')->send();

        $criteria = new \CDbCriteria();
        $criteria->select = ['event_id', 'result_field', 'value'];
        $criteria->addCondition('event_id = :event_id');
        $criteria->params = [':event_id' => $Event->getId()];
        $event_result_list = \SportEventResult::model()
            ->getCommandBuilder()
            ->createFindCommand(\SportEventResult::model()->tableName(), $criteria)
            ->queryAll();
        $event_result = [];
        foreach ($event_result_list as $result)
            $event_result[$result['result_field']] = $result['value'];
        $Event->getResult()->populateRecord($event_result);
        unset($event_result_list, $event_result);

        $params = [
            'Event' => $Event
        ];

        Yii::app()->getAjax()
            ->addReplace('tools/recalc/_recalc', '#content-replacement #event-recalc-body', $params)
            ->addTrigger('page:loaded')
            ->send();
    }
}