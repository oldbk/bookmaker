<?php
namespace frontend\widgets\event;
use CWidget;

class EventWidget extends CWidget
{
    public function init()
    {
        \Yii::app()->getStatic()->setWidget('event')
            ->registerCssFile('style.css', !YII_DEBUG)
            ->registerScriptFile('script.js', \CClientScript::POS_END, !YII_DEBUG);
    }

    public function run()
    {
        $criteria = new \CDbCriteria();
        $criteria->addCondition('is_top = 1');
        $criteria->index = 'title';
        /** @var \Team[] $TopTeam */
        $TopTeam = \Team::model()->cache(3600)->findAll($criteria);

        $CacheEvent = [
            0 => \Yii::app()->getCache()->get('event_widget_0'),
            1 => \Yii::app()->getCache()->get('event_widget_1'),
        ];
        $limit = 2;
        foreach ($CacheEvent as $Item) {
            if($Item !== false)
                $limit--;
        }

        if($limit) {
            $teams = array_keys($TopTeam);
            $criteriaMain = new \CDbCriteria();
            $criteria = new \CDbCriteria();
            if(!empty($teams)) {
                $criteria->addInCondition('team_1', $teams, 'OR');
                $criteria->addInCondition('team_2', $teams, 'OR');
                $criteria->addCondition('1=1', 'OR');
                $criteriaMain->mergeWith($criteria, 'OR');
            }

            $criteriaMain->addCondition('date_int < :time');
            $criteriaMain->addCondition('(select count(id) from user_betting ub where ub.event_id = `t`.id) > 3');
            $criteriaMain->scopes = ['for_user'];
            $criteriaMain->limit = $limit;
            $criteriaMain->params[':time'] = strtotime('+4 hour');


            //echo '<pre>';
            //var_dump($criteriaMain);die;
            /** @var \SportEvent[] $_EventList */
            $_EventList = \SportEvent::model()->findAll($criteriaMain);
            foreach ($_EventList as $Event) {
                if($CacheEvent[0] === false) {
                    $CacheEvent[0] = $Event;
                    \Yii::app()->getCache()->set('event_widget_0', $CacheEvent[0], $Event->getDateInt() - time());
                } elseif($CacheEvent[1] === false) {
                    $CacheEvent[1] = $Event;
                    \Yii::app()->getCache()->set('event_widget_1', $CacheEvent[1], $Event->getDateInt() - time());
                }
            }
        }

        /** @var \iSportEvent[] $EventList */
        $EventList = [];
        foreach ($CacheEvent as $Event) {
            if($Event === false) continue;

            $EventList[$Event->getId()] = $Event;
        }

        $criteria = new \CDbCriteria();
        $criteria->addInCondition('`t`.type', ['ratio_p1', 'ratio_p2', 'ratio_x']);
        $new_ratio = \SportEventRatio::getByIds(array_keys($EventList), \SportEventRatio::POSITION_NEW, $criteria);
        $last_ratio = \SportEventRatio::getByIds(array_keys($EventList), \SportEventRatio::POSITION_LAST, $criteria);
        foreach ($EventList as $Event) {
            if(isset($new_ratio[$Event->getId()]))
                $Event->getNewRatio()->populateRecord($new_ratio[$Event->getId()], true);
            if(isset($last_ratio[$Event->getId()]))
                $Event->getOldRatio()->populateRecord($last_ratio[$Event->getId()], true);
        }

        $this->render('index', ['EventList' => $EventList]);
    }
} 