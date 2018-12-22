<?php
namespace frontend\modules\admin\controllers\actions\event;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use common\components\VarDumper;
use common\interfaces\iStatus;
use Yii;
use BettingGroup;
use CDbCriteria;

class AllBettingAction extends CAction
{
    public $admin_part = false;

    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        $placeholder = [
            'user' => null,
            'bet-type' => null,
            'start' => null,
            'end' => null,
            'finish' => null,
            'event_id' => null,
            'bet_id' => null,
            'no_refund' => null,
            'ratio_type' => null,
            'price-type' => null,
            'user_login' => null
        ];
        $filter = \CMap::mergeArray($placeholder, Yii::app()->getRequest()->getParam('Filter', []));

        $criteria = new CDbCriteria();
        $criteria->select = '`t`.bet_group_id';
        $criteria->with = [
            'betGroup' => [
                'select' => false
            ]
        ];
        $criteria->index = 'bet_group_id';
        $criteria->group = '`t`.bet_group_id';
        $criteria->order = '`t`.id desc';
        if ($filter['user'] > 0) {
            $criteria->addCondition('`betGroup`.user_id = :user_id');
            $criteria->params[':user_id'] = $filter['user'];
        }
        if (!empty($filter['bet-type']) || $filter['bet-type'] == '0') {
            $criteria->addCondition('`betGroup`.bet_type = :bet_type');
            $criteria->params[':bet_type'] = $filter['bet-type'];
        }
        if (!empty($filter['start'])) {
            $criteria->addCondition('`t`.date_int >= :start');
            $criteria->params[':start'] = strtotime($filter['start'] . ' 00:00:00');
        }
        if (!empty($filter['end'])) {
            $criteria->addCondition('`t`.date_int <= :end');
            $criteria->params[':end'] = strtotime($filter['end'] . ' 23:59:59');
        }
        if (isset($filter['finish']) && $filter['finish'] !== null) {
            $criteria->addCondition('`betGroup`.status = :finish');
            $criteria->params[':finish'] = iStatus::STATUS_FINISH;
        }
        if (!empty($filter['event_id'])) {
            $criteria->addCondition('`t`.event_id = :event_id');
            $criteria->params[':event_id'] = $filter['event_id'];
        }
        if (!empty($filter['bet_id'])) {
            $criteria->addCondition('`betGroup`.user_group_number = :bet_id');
            $criteria->params[':bet_id'] = $filter['bet_id'];
        }
        if (!empty($filter['no_refund'])) {
            $criteria->addCondition('`betGroup`.is_refund != 1');
        }
        if (!empty($filter['ratio_type']) && $filter['ratio_type'] != '0') {
            $criteria->addCondition('`t`.ratio_type = :ratio_type');
            $criteria->params[':ratio_type'] = $filter['ratio_type'];
        }
        if (!empty($filter['price-type']) || $filter['price-type'] == '0') {
            $criteria->addCondition('`betGroup`.price_type = :price_type');
            $criteria->params[':price_type'] = $filter['price-type'];
        }

        $pages = new \CPagination(\UserBetting::model()->count($criteria));
        $pages->pageSize = 20;
        $pages->applyLimit($criteria);

        /** @var \UserBetting[] $models */
        $models = \UserBetting::model()->findAll($criteria);
        $ids = array_keys($models);
        unset($models, $criteria);

        $criteria = new CDbCriteria();
        $criteria->index = 'id';
        $criteria->order = '`t`.id desc';
        $criteria->with = ['user'];
        $criteria->addInCondition('`t`.id', $ids);
        /** @var BettingGroup[] $models */
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
        foreach ($event_result_list as $result) {
            $event_result[$result['event_id']][$result['result_field']] = $result['value'];
            $event_result[$result['event_id']]['event_id'] = $result['event_id'];
        }
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
        unset($EventList);
        $params = [
            'groups' => $models,
            'betting' => $user_betting_list,
            'events' => $event_list,
            'pages' => $pages,
            'filter' => $filter,
        ];

        if (Yii::app()->getRequest()->getIsAjaxRequest())
            Yii::app()->getAjax()
                ->addTrigger('page:loaded')
                ->addReplace('all_betting', '#content-replacement', $params)
                ->send();
        else
            $this->getController()->render('all_betting', $params);
    }
}