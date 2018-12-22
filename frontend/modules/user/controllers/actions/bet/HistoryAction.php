<?php
namespace frontend\modules\user\controllers\actions\bet;

use CAction;
use common\interfaces\iStatus;
use frontend\components\FrontendController;
use Yii;
use BettingGroup;
use CDbCriteria;

/**
 * Class HistoryAction
 * @package frontend\modules\user\controllers\actions\bet
 *
 * @method FrontendController getController()
 */
class HistoryAction extends CAction
{
    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        $placeholder = [
            'bet-type' => null,
            'start' => null,
            'end' => null,
            'new' => null,
            'bet-num' => null
        ];
        $filter = \CMap::mergeArray($placeholder, Yii::app()->getRequest()->getParam('Filter', []));

        $criteria = new CDbCriteria();
        $criteria->index = 'id';
        $criteria->scopes = ['own'];
        $criteria->order = '`t`.user_group_number desc';
        if (!empty($filter['bet-type']) || $filter['bet-type'] == '0') {
            $criteria->addCondition('`t`.bet_type = :bet_type');
            $criteria->params[':bet_type'] = $filter['bet-type'];
        }
        if (!empty($filter['start'])) {
            $criteria->addCondition('`t`.create_at >= :start');
            $criteria->params[':start'] = strtotime($filter['start'] . ' 00:00:00');
        }
        if (!empty($filter['end'])) {
            $criteria->addCondition('`t`.create_at <= :end');
            $criteria->params[':end'] = strtotime($filter['end'] . ' 23:59:59');
        }
        if ($filter['new'] == 'on') {
            $criteria->addCondition('`t`.result_status = :new');
            $criteria->params[':new'] = iStatus::RESULT_NEW;
        }
        if (!empty($filter['bet-num'])) {
            $criteria->addCondition('`t`.user_group_number = :bet_num');
            $criteria->params[':bet_num'] = $filter['bet-num'];
        }

        $pages = new \CPagination(\BettingGroup::model()->count($criteria));
        $pages->pageSize = 15;
        $pages->applyLimit($criteria);

        $models = BettingGroup::model()->findAll($criteria);

        $criteria = new CDbCriteria();
        $criteria->addInCondition('`t`.bet_group_id', array_keys($models));
        $criteria->with = [
            'eventRatio' => [
                'select' => ['type', 'value']
            ]
        ];
        /** @var \UserBetting[] $UserBetting */
        $UserBetting = \UserBetting::model()->findAll($criteria);
        $user_betting_list = [];
        $event_ids = [];
        $event_list_by_group = [];
        foreach ($UserBetting as $Bet) {
            if (!in_array($Bet->getEventId(), $event_ids))
                $event_ids[] = $Bet->getEventId();
            $user_betting_list[$Bet->getBetGroupId()][] = $Bet;

            $temp = [];
            foreach ($Bet->eventRatio as $Ratio)
                $temp[$Ratio->getType()] = $Ratio->getValue();
            $event_list_by_group[$Bet->getBetGroupId()][$Bet->getEventId()] = [
                'items' => $temp,
                'dop_ratio' => $Bet->getDopRatio(),
                'price_type' => $Bet->getPriceType()
            ];
        }

        $criteria = new CDbCriteria();
        $criteria->select = ['event_id', 'result_field', 'value'];
        $criteria->addInCondition('`t`.event_id', $event_ids);
        $event_result_list = \SportEventResult::model()
            ->getCommandBuilder()
            ->createFindCommand(\SportEventResult::model()->tableName(), $criteria)
            ->queryAll();
        $event_result = [];
        foreach ($event_result_list as $result)
            $event_result[$result['event_id']][$result['result_field']] = $result['value'];
        unset($event_result_list);

        $criteria = new CDbCriteria();
        $criteria->with = ['sport'];
        $criteria->index = 'id';
        $criteria->addInCondition('`t`.id', $event_ids);
        /** @var \SportEvent[] $EventList */
        $EventList = \SportEvent::model()->findAll($criteria);
        $event_list = [];
        foreach ($event_list_by_group as $bet_group_id => $_temp) {
            $temp = [];
            foreach ($_temp as $event_id => $ratio_list) {
                $Event = $EventList[$event_id]->copy();

                $Event->getNewRatio()->populateRecord($ratio_list['items'], true, $ratio_list['dop_ratio'], $ratio_list['price_type']);
                if(isset($event_result[$event_id])) {
                    $Event->getResult()->populateRecord($event_result[$event_id]);
                }
                $temp[$event_id] = $Event;
            }

            $event_list[$bet_group_id] = $temp;
        }

        $params = [
            'groups' => $models,
            'betting' => $user_betting_list,
            'events' => $event_list,
            'pages' => $pages,
            'filter' => $filter
        ];

        $this->getController()->setPageTitle('История ставок');
        if (Yii::app()->getRequest()->getIsAjaxRequest()) {
            $view = 'history';
            if($pages->getCurrentPage() > 0)
                $view = 'page/history';

            Yii::app()->getAjax()
                ->addTrigger('page:loaded')
                ->addReplace($view, '#content-replacement', $params)
                ->send();
        } else
            $this->getController()->render('history', $params);
    }
}