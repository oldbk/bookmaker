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

class CheckAction extends CAction
{
    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        $returned = [];
        $post = Yii::app()->getRequest()->getPost('List', []);
        $bet_id = Yii::app()->getRequest()->getPost('bet_id');

        $criteria = new \CDbCriteria();
        $criteria->addCondition('bet_group_id = :id');
        $criteria->with = [
            'eventRatio' => [
                'select' => ['type', 'value']
            ]
        ];
        $criteria->params = [':id' => $bet_id];
        /** @var \UserBetting[] $BettingList */
        $BettingList = \UserBetting::model()->findAll($criteria);

        $criteria = new \CDbCriteria();
        $criteria->addInCondition('id', array_keys($post));
        $criteria->index = 'id';
        /** @var \iSportEvent[] $EventList */
        $EventList = \SportEvent::model()->findAll($criteria);
        if(count($BettingList) != count($EventList))
            Yii::app()->getAjax()->addErrors('Кол-во событий не совпадают с событиями в ставке')->send();

        foreach ($BettingList as $Bet) {
            $temp = [];
            foreach ($Bet->eventRatio as $Ratio)
                $temp[$Ratio->getType()] = $Ratio->getValue();

            $Event = $EventList[$Bet->getEventId()];

            $Event->getNewRatio()->populateRecord($temp, true, $Bet->getDopRatio(), $Bet->getPriceType());
            $Event->getResult()->populateRecord($post[$Bet->getEventId()]);
            $returned[$Bet->getEventId()] = [
                'result' => 1,
                'explain' => []
            ];

            if(!$Event->getResult()->isCancel()) {
                $ratio_val = $Event->getNewRatio()->getAttribute($Bet->getRatioType());

                /** @var \common\factories\ratio\_default\_interface\iRatio $CheckResult */
                $CheckResult = \common\factories\RatioFactory::factory($Event->getSportType(), $Bet->getRatioType());
                $CheckResult->setBet($Bet)
                    ->setRatioValue($ratio_val)
                    ->setEvent($Event)
                    ->check();

                $returned[$Bet->getEventId()]['explain'] = $CheckResult->getExplain();
                if($CheckResult->getStatus() == iStatus::RESULT_LOSS)
                    $returned[$Bet->getEventId()]['result'] = 0;
            }
        }

        Yii::app()->getAjax()
            ->addOther(['event_list' => $returned])
            ->addMessage('Симуляция прошла')
            ->send();
    }
}