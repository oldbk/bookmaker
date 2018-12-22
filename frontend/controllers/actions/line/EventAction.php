<?php
namespace frontend\controllers\actions\line;

use CAction;
use frontend\components\FrontendController;
use Yii;

/**
 * Class EventAction
 * @package frontend\controllers\actions\line
 *
 * @method FrontendController getController()
 */
class EventAction extends CAction
{
    public $admin_part = false;

    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        $criteria = new \CDbCriteria();
        $criteria->addCondition('`t`.id = :id');
        $criteria->params = [':id' => Yii::app()->getRequest()->getParam('event_id')];
        if(!Yii::app()->user->isAdmin()) {
            $criteria->scopes = ['for_user'];
        }
        $criteria->with = ['ratioFixed'];
        /** @var \SportEvent $SportEvent */
        $SportEvent = \SportEvent::model()->find($criteria);
        if (!$SportEvent)
            Yii::app()->getAjax()->addErrors('Событие на найдено')->send();

        $criteria = new \CDbCriteria();
        $criteria->select = ['type', 'value'];
        $criteria->addCondition('`t`.event_id = :event_id');
        $criteria->addCondition('`t`.position = :position');
        $criteria->params[':event_id'] = $SportEvent->getId();
        $criteria->params[':position'] = \SportEventRatio::POSITION_NEW;
        $new_ratio = \SportEventRatio::model()
            ->getCommandBuilder()
            ->createFindCommand(\SportEventRatio::model()->tableName(), $criteria)
            ->queryAll();
        $new_ratio_list = [];
        foreach ($new_ratio as $ratio)
            $new_ratio_list[$ratio['type']] = $ratio['value'];

        $criteria->params[':position'] = \SportEventRatio::POSITION_LAST;
        $last_ratio = \SportEventRatio::model()
            ->getCommandBuilder()
            ->createFindCommand(\SportEventRatio::model()->tableName(), $criteria)
            ->queryAll();
        $last_ratio_list = [];
        foreach ($last_ratio as $ratio)
            $last_ratio_list[$ratio['type']] = $ratio['value'];

        $SportEvent->getNewRatio()->populateRecord($new_ratio_list, true);
        $SportEvent->getOldRatio()->populateRecord($last_ratio_list, true);

        //last team1
        $criteria = new \CDbCriteria();
        $criteria->addCondition('`t`.sport_id = :sport');
        $criteria->addCondition('`t`.id != :id');
        $criteria->addCondition('`t`.have_result = 1');
        $criteria->addCondition('(`t`.team_1_id = :team_id and `t`.team_1_id) > 0 or `t`.team_1 = :team');
        $criteria->with = ['hasResult'];
        $criteria->index = 'id';
        $criteria->params = [
            ':team_id'  => $SportEvent->getTeam1Id(),
            ':team'     => $SportEvent->getTeam1(),
            ':id'       => $SportEvent->getId(),
            ':sport'    => $SportEvent->getSportId(),
        ];
        $criteria->limit = 5;
        $criteria->order = '`t`.date_int desc';

        $dependency = new \CDbCacheDependency('SELECT MAX(update_at) FROM {{sport_event}}');
        $dependency->reuseDependentData = true;
        /** @var \iSportEvent[] $team1games */
        $team1games = \SportEvent::model()->cache(3600, $dependency)->findAll($criteria);

        $criteria = new \CDbCriteria();
        $criteria->select = ['event_id', 'result_field', 'value'];
        $criteria->addInCondition('`t`.event_id', array_keys($team1games));
        $event_result_list = \SportEventResult::model()
            ->getCommandBuilder()
            ->createFindCommand(\SportEventResult::model()->tableName(), $criteria)
            ->queryAll();
        $event_result = [];
        foreach ($event_result_list as $result)
            $event_result[$result['event_id']][$result['result_field']] = $result['value'];
        unset($event_result_list);

        foreach ($team1games as $event_id => $Event) {
            if(isset($event_result[$event_id])) {
                $team1games[$event_id]->getResult()->populateRecord($event_result[$event_id]);
                /*if($team1games[$event_id]->getResult()->isCancel()) {
                    unset($team1games[$event_id]);
                }*/
            }

        }

        //last team2
        $criteria = new \CDbCriteria();
        $criteria->addCondition('`t`.sport_id = :sport');
        $criteria->addCondition('`t`.id != :id');
        $criteria->addCondition('`t`.have_result = 1');
        $criteria->addCondition('(`t`.team_2_id = :team_id and `t`.team_2_id > 0) or `t`.team_2 = :team');
        $criteria->with = ['hasResult'];
        $criteria->index = 'id';
        $criteria->params = [
            ':team_id'  => $SportEvent->getTeam2Id(),
            ':team'     => $SportEvent->getTeam2(),
            ':id'       => $SportEvent->getId(),
            ':sport'    => $SportEvent->getSportId(),
        ];
        $criteria->limit = 5;
        $criteria->order = '`t`.date_int desc';

        /** @var \iSportEvent[] $team2games */
        $team2games = \SportEvent::model()->cache(3600, $dependency)->findAll($criteria);

        $criteria = new \CDbCriteria();
        $criteria->select = ['event_id', 'result_field', 'value'];
        $criteria->addInCondition('`t`.event_id', array_keys($team2games));
        $event_result_list = \SportEventResult::model()
            ->getCommandBuilder()
            ->createFindCommand(\SportEventResult::model()->tableName(), $criteria)
            ->queryAll();
        $event_result = [];
        foreach ($event_result_list as $result)
            $event_result[$result['event_id']][$result['result_field']] = $result['value'];
        unset($event_result_list);

        foreach ($team2games as $event_id => $Event) {
            if(isset($event_result[$event_id])) {
                $team2games[$event_id]->getResult()->populateRecord($event_result[$event_id]);
                /*if($team2games[$event_id]->getResult()->isCancel()) {
                    unset($team2games[$event_id]);
                }*/
            }

        }

        $params = [
            'event'         => $SportEvent,
            'team1games'    => $team1games,
            'team2games'    => $team2games
        ];

        $this->getController()->setPageTitle($SportEvent->getTeam1() . ' - ' . $SportEvent->getTeam2());
        if (Yii::app()->getRequest()->getIsAjaxRequest()) {
            Yii::app()->getAjax()
                ->addReplace('event', '#content-replacement', $params)
                ->addTrigger('page:loaded')
                ->send();
        } else {
            $this->getController()->render('event', $params);
        }
    }
}