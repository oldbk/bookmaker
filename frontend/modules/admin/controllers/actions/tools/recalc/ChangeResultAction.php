<?php
namespace frontend\modules\admin\controllers\actions\tools\recalc;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use common\factories\TransferFactory;
use common\interfaces\iStatus;
use Yii;

class ChangeResultAction extends CAction
{
    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        $event_id = Yii::app()->getRequest()->getPost('event_id');
        $result = Yii::app()->getRequest()->getPost('Result');

        $t = Yii::app()->getDb()->beginTransaction();
        try {

            /** @var \iSportEvent $Event */
            $Event = \SportEvent::model()->findByPk($event_id);
            if(!$Event)
                throw new \Exception('Не удалось найти событие или проблема уже решена');

            $EventResult = $Event->getResult();
            $EventResult->populateRecord($result);

            if ((empty($EventResult->getTeam1Result()) && $EventResult->getTeam1Result() != 0)
                || (empty($EventResult->getTeam2Result()) && $EventResult->getTeam2Result() != 0)) {

                throw new \Exception('Некорректно проставленные голы');
            }
            if (!$EventResult->insert($Event->getId()))
                throw new \Exception('Не удалось зафиксировать результат');

            $log_message = $this->refundBet($Event->getId());

            $Event
                ->setStatus(iStatus::STATUS_FINISH)
                ->setHaveResult(1)
                ->save();

            $t->commit();

            Yii::app()->getAjax()
                ->addMessage('Пересчет запущен')
                ->addReplace('tools/recalc/_log', '#content-replacement #event-recalc-body #log', ['messages' => $log_message]);

        } catch (\Exception $ex) {
            $t->rollback();
            Yii::app()->getAjax()->addErrors($ex->getMessage());
        }

        Yii::app()->getAjax()->send();
    }

    private function refundBet($event_id)
    {
        $returned = [];

        $criteria = new \CDbCriteria();
        $criteria->addCondition('`userBetting`.event_id = :event_id');
        $criteria->addCondition('`t`.is_refund != 1');
        $criteria->with = ['userBetting', 'user'];
        $criteria->params = [
            ':event_id' => $event_id,
        ];
        /** @var \BettingGroup[] $BettingList */
        $BettingList = \BettingGroup::model()->findAll($criteria);
        foreach ($BettingList as $Group) {
            $price = $Group->getPaymentSum();

            $r = $Group->setStatus(\common\interfaces\iStatus::STATUS_NEW)
                ->setResultStatus(iStatus::STATUS_NEW)
                ->setPaymentSum(0.00)
                ->save();
            if(!$r)
                throw new \Exception('Не удалось сохранить группу');

            foreach ($Group->userBetting as $Bet) {
                $r = $Bet->setStatus(iStatus::STATUS_NEW)
                    ->setResultStatus(iStatus::STATUS_NEW)
                    ->save();
                if(!$r)
                    throw new \Exception('Не удалось сохранить ставку');

                if($price > 0) {
                    /** @var \common\factories\transfer\_interface\iBalance $Balance */
                    $Balance = TransferFactory::factory('balance', $Group->getPriceType(), [$Group->user]);

                    $msg = sprintf('Пересчет события №'.$event_id);
                    if(!$Balance->take($price, $msg)) {
                        throw new \Exception('Не удалось снять средства');
                    }

                    $returned[] = sprintf("Списали с баланса %s. У пользователя %s\n", $price, $Group->user->getLogin());
                }
            }
        }

        return $returned;
    }
}