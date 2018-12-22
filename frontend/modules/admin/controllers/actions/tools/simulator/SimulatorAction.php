<?php
namespace frontend\modules\admin\controllers\actions\tools\simulator;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use common\interfaces\iStatus;
use common\singletons\prices\Prices;
use Yii;

class SimulatorAction extends CAction
{
    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        $simulator = Yii::app()->getRequest()->getPost('Simulator', []);
        if(!isset($simulator['bet_id']))
            Yii::app()->getAjax()->addErrors('Вы не ввели № ставки')->send();

        /** @var \BettingGroup $BettingGroup */
        $BettingGroup = \BettingGroup::model()->with('user')->findByPk($simulator['bet_id']);
        if(!$BettingGroup)
            Yii::app()->getAjax()->addErrors('Ставка не найдена')->send();

        $criteria = new \CDbCriteria();
        $criteria->addCondition('bet_group_id = :id');
        $criteria->with = [
            'eventRatio' => [
                'select' => ['type', 'value']
            ]
        ];
        $criteria->params = [':id' => $simulator['bet_id']];
        /** @var \UserBetting[] $BettingList */
        $BettingList = \UserBetting::model()->findAll($criteria);
        $event_ratio_list = $event_ids = [];

        foreach ($BettingList as $Bet) {
            $event_ids[$Bet->getEventId()] = $Bet->getRatioType();

            $temp = [];
            foreach ($Bet->eventRatio as $Ratio)
                $temp[$Ratio->getType()] = $Ratio->getValue();
            $event_ratio_list[$Bet->getEventId()] = [
                'items' => $temp,
                'dop_ratio' => $Bet->getDopRatio(),
                'price_type' => $Bet->getPriceType()
            ];
        }

        $criteria = new \CDbCriteria();
        $criteria->addInCondition('id', array_keys($event_ids));
        $criteria->index = 'id';
        /** @var \iSportEvent[] $EventList */
        $EventList = \SportEvent::model()->findAll($criteria);
        if(count($EventList) != count($event_ids))
            Yii::app()->getAjax()->addErrors('События не найдены')->send();

        foreach ($EventList as $Event) {
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

            $ratio_list = $event_ratio_list[$Event->getId()];
            $Event->getNewRatio()->populateRecord($ratio_list['items'], true, $ratio_list['dop_ratio'], $ratio_list['price_type']);
            unset($event_result_list, $event_result);
        }

        $params = [
            'BettingGroup' => $BettingGroup,
            'EventList' => $EventList,
            'Price' => Prices::init($BettingGroup->getPriceType()),
            'betting_type' => $event_ids,
        ];

        Yii::app()->getAjax()
            ->addReplace('tools/simulator/_result', '#content-replacement #event-simulator-body', $params)
            ->send();
    }
}