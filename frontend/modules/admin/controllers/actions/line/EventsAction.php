<?php
namespace frontend\modules\admin\controllers\actions\line;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use common\interfaces\iStatus;
use Yii;
use Sport;

class EventsAction extends CAction
{
    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        $line = Yii::app()->getRequest()->getParam('line_id');
        $Sport = Sport::model()->findByPk($line);
        if (!$Sport)
            Yii::app()->ajax->addErrors('Линия не найдена')->send();

        $criteria = new \CDbCriteria();
        $criteria->addCondition('sport_id = :sport_id');
        $criteria->addCondition('status != :status');
        $criteria->scopes = ['nTrash', 'nHaveProblem'];
        $criteria->index = 'id';
        $criteria->order = 't.date_int asc';
        $criteria->params = [':sport_id' => $Sport->getId(), ':status' => iStatus::STATUS_FINISH];

        /** @var \SportEvent[] $EventList */
        $EventList = \SportEvent::model()->findAll($criteria);

        $criteria = new \CDbCriteria();
        $criteria->select = ['event_id', 'type', 'value'];
        $criteria->addInCondition('`t`.event_id', array_keys($EventList));
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

        foreach ($EventList as $Event) {
            if (isset($new_ratio_list[$Event->getId()]))
                $Event->getNewRatio()->populateRecord($new_ratio_list[$Event->getId()]);
            if (isset($last_ratio_list[$Event->getId()]))
                $Event->getOldRatio()->populateRecord($last_ratio_list[$Event->getId()]);
        }

        $params = [
            'models' => $EventList,
            'sport' => $Sport,
        ];

        Yii::app()->getStatic()->setLibrary('editable')
            ->registerCssFile('bootstrap-editable.css')
            ->registerScriptFile('bootstrap-editable.min.js');

        if (Yii::app()->getRequest()->getIsAjaxRequest())
            Yii::app()->getAjax()
                ->addTrigger('page:loaded')
                ->addReplace('events', '#content-replacement', $params)
                ->send();
        else
            $this->getController()->render('events', $params);
    }
}