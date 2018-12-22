<?php
use \common\interfaces\iStatus;
use \common\factories\TransferFactory;

/**
 * Created by PhpStorm.
 */
class ToolsCommand extends CConsoleCommand
{
    public function actionRevert($event_id)
    {
        echo sprintf("Событие #%s\n", $event_id);

        $this->revert($event_id);
    }

    private function revert($event_id, $revert_bet = true)
    {
        $t = null;
        if(Yii::app()->db->getCurrentTransaction() == null) {
            $t = Yii::app()->db->beginTransaction();
        }
        try {
            /** @var iSportEvent $Event */
            $Event = SportEvent::model()->findByPk($event_id);
            if(!$Event)
                throw new Exception('Событие не найдено');

            $criteria = new CDbCriteria();
            $criteria->addCondition('`userBetting`.event_id = :event_id');
            $criteria->addCondition('`t`.is_refund != 1');
            $criteria->with = ['userBetting', 'user'];
            $criteria->params = [
                ':event_id' => $Event->getId(),
            ];
            /** @var BettingGroup[] $BettingList */
            $BettingList = BettingGroup::model()->findAll($criteria);
            echo sprintf("Кол-во ставок: %d\n", count($BettingList));
            foreach ($BettingList as $Group) {
                $price = $Group->getPaymentSum();

                $r = $Group->setStatus(\common\interfaces\iStatus::STATUS_NEW)
                    ->setResultStatus(iStatus::STATUS_NEW)
                    ->setPaymentSum(0.00)
                    ->save();
                if(!$r)
                    throw new Exception('Не удалось сохранить группу');

                foreach ($Group->userBetting as $Bet) {
                    if($revert_bet == true) {
                        $r = $Bet->setStatus(iStatus::STATUS_NEW)
                            ->setResultStatus(iStatus::STATUS_NEW)
                            ->save();
                        if(!$r)
                            throw new Exception('Не удалось сохранить ставку');
                    }

                    if($price > 0) {
                        /** @var \common\factories\transfer\_interface\iBalance $Balance */
                        $Balance = TransferFactory::factory('balance', $Group->getPriceType(), [$Group->user]);

                        $msg = sprintf('Пересчет события №'.$Event->getId());
                        if(!$Balance->take($price, $msg)) {
                            throw new Exception('Не удалось снять средства');
                        }

                        echo sprintf("Списали с баланса %s. У пользователя %s\n", $price, $Group->user->getLogin());
                    }
                }
            }

            if($t !== null) {
                $t->commit();
            }
            return true;
        } catch (Exception $ex) {
            if($t !== null) {
                $t->rollback();
            }
            var_dump($ex->getMessage());
        }

        return false;
    }

    public function actionFixes()
    {
        $revert_ids = array();

        $t = Yii::app()->db->beginTransaction();
        try {
            $starttime = new DateTime('2016-03-27 03:00:00');
            $lasttime = new DateTime('2016-03-27 22:00:00');

            $criteria = new CDbCriteria();
            $criteria->with = array('eventOriginal', 'user', 'betGroup');
            $criteria->addCondition('eventOriginal.date_int <= :lasttime and eventOriginal.date_int >= :starttime');
            $criteria->addCondition('eventOriginal.date_int - t.create_at < 3600');
            $criteria->params = array(
                ':lasttime' => $lasttime->getTimestamp(),
                ':starttime' => $starttime->getTimestamp(),
            );
            $BetList = UserBetting::model()->findAll($criteria);
            foreach ($BetList as $Bet) {
                if(!in_array($Bet->event_id, $revert_ids)) {
                    $revert_ids[] = $Bet->event_id;
                }
                $Bet->setRatioValue(1.00);
                if(!$Bet->save()) {
                    throw new Exception();
                }
            }

            foreach ($revert_ids as $event_id) {
                if(!$this->revert($event_id, false)) {
                    throw new Exception();
                }
            }

            $t->commit();
            var_dump('done');
        } catch (Exception $ex) {
            $t->rollback();
            var_dump('error');
        }
    }

    public function actionFixes2()
    {
        $event_ids = array(
            116690,
            116010,
            116356,
            116381,
            116734,
            117468,
            117295,
            116745,
            115653,
            117017,
            116336,
            116831
        );
        $criteria = new CDbCriteria();
        $criteria->addInCondition('id', $event_ids);
        $t = Yii::app()->db->beginTransaction();
        try {
            $EventList = SportEvent::model()->findAll($criteria);
            foreach ($EventList as $Event) {
                $date = new DateTime();
                $date->setTimestamp($Event->date_int)
                    ->modify('-1 hour');
                $Event->date_int = $date->getTimestamp();
                if(!$Event->save()) {
                    throw new Exception();
                }
            }

            $t->commit();
            var_dump('done');
        } catch (Exception $ex) {
            $t->rollback();
            var_dump('error');
        }
    }

    public function actionFixes3()
    {
        $revert_ids = array();

        $t = Yii::app()->db->beginTransaction();
        try {
            $starttime = new DateTime('2016-03-27 03:00:00');

            $criteria = new CDbCriteria();
            $criteria->with = array('eventOriginal', 'user', 'betGroup');
            $criteria->addCondition('betGroup.create_at >= :starttime');
            $criteria->addCondition('betGroup.create_at > eventOriginal.date_int');
            $criteria->params = array(
                ':starttime' => $starttime->getTimestamp(),
            );
            $BetList = UserBetting::model()->findAll($criteria);
            foreach ($BetList as $Bet) {
                if(!in_array($Bet->event_id, $revert_ids)) {
                    $revert_ids[] = $Bet->event_id;
                }
                $Bet->setRatioValue(1.00);
                if(!$Bet->save()) {
                    throw new Exception();
                }
            }

            foreach ($revert_ids as $event_id) {
                if(!$this->revert($event_id, false)) {
                    throw new Exception();
                }
            }

            $t->commit();
            var_dump('done');
        } catch (Exception $ex) {
            $t->rollback();
            var_dump('error');
        }
    }
}