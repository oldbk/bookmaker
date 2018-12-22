<?php
namespace frontend\modules\admin\controllers\actions\event;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use Yii;
use Sport;

class HistoryAction extends CAction
{
    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        $event_id = Yii::app()->getRequest()->getParam('event_id');
        $line_id = Yii::app()->getRequest()->getParam('line_id');

        /** @var \Sport $Sport */
        $Sport = \Sport::model()->findByPk($line_id);
        if (!$Sport)
            Yii::app()->ajax->addErrors('Линия не найдена')->send();

        $criteria = new \CDbCriteria();
        $criteria->addCondition('`t`.id = :id');
        $criteria->with = ['sport'];
        $criteria->params = [':id' => $event_id];
        /** @var \SportEvent $Event */
        $Event = \SportEvent::model()->find($criteria);
        if (!$Event)
            Yii::app()->getAjax()->addErrors('Событие не найдено')->send();

        /** @var \SportEvent[] $models */
        $models = [];

        $criteria = new \CDbCriteria();
        $criteria->select = ['_v', 'value', 'type', 'create_at'];
        $criteria->addCondition('event_id = :event_id');
        $criteria->params = [':event_id' => $Event->getId()];
        $RatioList = \SportEventRatio::model()->findAll($criteria);
        $ratio_list = [];
        foreach ($RatioList as $Ratio) {
            $ratio_list[$Ratio->getV()][$Ratio->getType()] = $Ratio->getValue();
            $ratio_list[$Ratio->getV()]['create_at'] = $Ratio->getCreateAt();
        }
        foreach ($ratio_list as $v => $ratio) {
            $models[$v] = $Event->copy();
            $models[$v]->setV($v);
            $models[$v]->getNewRatio()->populateRecord($ratio);
        }

        $params = [
            'models' => $models,
            'sport' => $Sport,
        ];

        if (Yii::app()->getRequest()->getIsAjaxRequest())
            Yii::app()->getAjax()
                ->addTrigger('page:loaded')
                ->addReplace('events_history', '#content-replacement', $params)
                ->send();
        else
            $this->getController()->render('events_history', $params);
    }
}