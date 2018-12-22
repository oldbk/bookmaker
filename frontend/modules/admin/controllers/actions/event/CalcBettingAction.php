<?php
namespace frontend\modules\admin\controllers\actions\event;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use common\interfaces\iPrice;
use common\interfaces\iStatus;
use Yii;
use BettingGroup;
use CDbCriteria;

class CalcBettingAction extends CAction
{
    public $admin_part = false;

    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        $filter = Yii::app()->getRequest()->getParam('Filter', [
            'user' => null,
            'bet-type' => null,
            'start' => null,
            'end' => null,
            'finish' => null,
            'event_id' => null,
            'bet_id' => null,
            'no_refund' => null,
            'ratio_type' => null,
        ]);
        $kr = 0;
        $ekr = 0;

        $criteriaEvent = new CDbCriteria();
        $criteriaEvent->group = 'bet_group_id';

        $criteria = new CDbCriteria();
        $criteria->select = 'sum(`t`.price) as sum';
        //$criteria->join = 'INNER JOIN (SELECT max(ub.id), ub.* FROM user_betting ub) userBettingOne ON userBettingOne.bet_group_id = t.id';
        if ($filter['user'] > 0) {
            $criteria->addCondition('`t`.user_id = :user_id');
            $criteria->params[':user_id'] = $filter['user'];
        }
        if (!empty($filter['bet-type']) || $filter['bet-type'] == '0') {
            $criteria->addCondition('`t`.bet_type = :bet_type');
            $criteria->params[':bet_type'] = $filter['bet-type'];
        }
        if (!empty($filter['start'])) {
            $criteriaEvent->addCondition('date_int >= :start');
            $criteria->params[':start'] = strtotime($filter['start'] . ' 00:00:00');
        }
        if (!empty($filter['end'])) {
            $criteriaEvent->addCondition('date_int <= :end');
            $criteria->params[':end'] = strtotime($filter['end'] . ' 23:59:59');
        }
        if (isset($filter['finish']) && $filter['finish'] !== null) {
            $criteria->addCondition('`t`.status = :finish');
            $criteria->params[':finish'] = iStatus::STATUS_FINISH;
        }
        if (!empty($filter['event_id'])) {
            $criteriaEvent->addCondition('event_id = :event_id');
            $criteria->params[':event_id'] = $filter['event_id'];
        }
        if (!empty($filter['bet_id'])) {
            $criteria->addCondition('`t`.user_group_number = :bet_id');
            $criteria->params[':bet_id'] = $filter['bet_id'];
        }
        if (!empty($filter['no_refund'])) {
            $criteria->addCondition('`t`.is_refund != 1');
        }
        if (!empty($filter['ratio_type']) && $filter['ratio_type'] != '0') {
            $criteriaEvent->addCondition('ratio_type = :ratio_type');
            $criteria->params[':ratio_type'] = $filter['ratio_type'];
        }

        $criteria->addCondition('`t`.price_type = :price_type');
        $criteria->params[':price_type'] = iPrice::TYPE_KR;

        $model = new \UserBetting();
        $subQuery = $model->getCommandBuilder()->createFindCommand($model->getTableSchema(), $criteriaEvent)->getText();
        $criteria->join = sprintf('INNER JOIN (%s) userBettingOne ON userBettingOne.bet_group_id = t.id', $subQuery);

        //var_dump($criteria);die;

        //$subQuery = BettingGroup::model()->getCommandBuilder()->createFindCommand(BettingGroup::model()->getTableSchema(), $criteria)->getText();
        //var_dump($subQuery);
        //var_dump($criteria->params);die;

        /** @var BettingGroup $model */
        $model = BettingGroup::model()->find($criteria);
        if ($model)
            $kr = $model->sum;

        $criteria->params[':price_type'] = iPrice::TYPE_EKR;
        /** @var BettingGroup $model */
        $model = BettingGroup::model()->find($criteria);
        if ($model)
            $ekr = $model->sum;

        Yii::app()->ajax
            ->addHtml('_calc_betting', '#betting-calc', ['kr' => $kr, 'ekr' => $ekr])
            ->send();
    }
}