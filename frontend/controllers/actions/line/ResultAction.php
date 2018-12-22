<?php
namespace frontend\controllers\actions\line;

use CAction;
use frontend\components\FrontendController;
use Yii;
use Sport;

/**
 * Class ResultAction
 * @package frontend\controllers\actions\line
 *
 * @method FrontendController getController()
 */
class ResultAction extends CAction
{
    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        $datetime = Yii::app()->getRequest()->getParam('datetime', date('d.m.Y'));

        $criteria = new \CDbCriteria();
        $criteria->scopes = ['have_result'];
        $criteria->index = 'id';
        $criteria->order = 't.date_int asc';
        $criteria->addCondition("`t`.date_int >= :date_begin");
        $criteria->addCondition("`t`.date_int <= :date_end");
        $criteria->params = [
            ':date_begin' => strtotime($datetime . ' 00:00:00'),
            ':date_end' => strtotime($datetime . ' 23:59:59'),
        ];
        $dependency = new \CDbCacheDependency('SELECT MAX(update_at) FROM {{sport_event}}');
        $dependency->reuseDependentData = true;
        /** @var \iSportEvent[] $EventList */
        $EventList = \SportEvent::model()->cache(3600, $dependency)->findAll($criteria);

        $criteria = new \CDbCriteria();
        $criteria->select = ['event_id', 'result_field', 'value'];
        $criteria->addInCondition('event_id', array_keys($EventList));
        $event_result_list = \SportEventResult::model()
            ->getCommandBuilder()
            ->createFindCommand(\SportEventResult::model()->tableName(), $criteria)
            ->queryAll();
        $event_result = [];
        foreach ($event_result_list as $result)
            $event_result[$result['event_id']][$result['result_field']] = $result['value'];
        unset($event_result_list);

        $sport_ids = $List = [];
        foreach ($EventList as $event_id => $Event) {
            if(isset($event_result[$event_id]))
                $Event->getResult()->populateRecord($event_result[$event_id]);

            $List[$Event->getSportId()][$Event->getId()] = $Event;
            if (!in_array($Event->getSportId(), $sport_ids))
                $sport_ids[] = $Event->getSportId();
        }
        unset($EventList);

        $criteria = new \CDbCriteria();
        $criteria->order = '`t`.title asc';
        $criteria->addInCondition('id', $sport_ids);
        /** @var \Sport[] $models */
        $models = \Sport::model()->findAll($criteria);

        $params = [
            'sports' => $models,
            'events' => $List,
            'datetime' => $datetime
        ];

        $this->getController()->setPageTitle('Результаты событий');
        if (Yii::app()->getRequest()->isAjaxRequest)
            Yii::app()->getAjax()
                ->addTrigger('page:loaded')
                ->addReplace('result', '#content-replacement', $params)
                ->send();
        else
            $this->getController()->render('result', $params);
    }
}