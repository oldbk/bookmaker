<?php
/**
 * Created by PhpStorm.
 */

namespace common\factories\transfer\_base\kr_ekr;


use common\components\NException;
use common\helpers\Convert;
use common\interfaces\iPrice;
use common\interfaces\iStatus;
use Yii;

abstract class BaseModer extends Base
{
    /**
     * @param $price
     * @return \User
     */
    abstract protected function takeBalance($price);

    /**
     * @param $price
     * @return \User
     */
    abstract protected function addBalance($price);

    /**
     * @return float
     */
    abstract protected function getBalance();

    /**
     * @param $game_id
     * @param $price
     * @param $bank_id
     * @return boolean
     */
    abstract protected function addToGame($game_id, $price, $bank_id = null);

    public function run($price)
    {
        /** @var float $price */
        $price = Convert::getMoneyFormat($price);
        $t = null;
        if(Yii::app()->db->currentTransaction === null)
            $t = Yii::app()->db->beginTransaction();
        try {
            //создаем запись о выводе
            $UserBalance = new \UserBalanceOutput();
            $r = $UserBalance
                ->setUserId($this->getUserId())
                ->setPrice($price)
                ->setPriceType($this->getPriceType())
                ->setOperationType(iPrice::OPERATION_TYPE_TAKE)
                ->setIsModer(1)
                ->setBankId($this->getBankId())
                ->setBalanceBefore($this->getBalance())
                ->validate();
            if(!$r)
                throw new NException(
                    'Не удалось сохранить историю по операции.', NException::ERROR_FINANCE_OUT, ['errors' => $UserBalance->getErrors()]);

            $UserRequest = new \UserOutputRequest();
            $UserRequest->setUserId($this->getUserId())
                ->setPrice($price)
                ->setPriceType($this->getPriceType())
                ->setStatus(\UserOutputRequest::STATUS_NEW)
                ->setBankId($this->getBankId());
            if(!$UserRequest->validate())
                throw new NException(
                    'Не удалось создать реквест на вывод', NException::ERROR_FINANCE_OUT, ['errors' => $UserRequest->getErrors()]);

            $r = $this
                ->takeBalance($UserBalance->getPrice())
                ->validate();
            if(!$r)
                throw new NException(
                    'Не удалось добавить средства на счет', NException::ERROR_FINANCE_OUT, ['errors' => $this->getUser()->getErrors()]);

            $UserBalance->setBalanceAfter($this->getBalance());
            if(!$this->getUser()->save() || !$UserBalance->save())
                throw new NException('Не удалось сохранить операцию', NException::ERROR_FINANCE_OUT);

            $r = $UserRequest
                ->setBalanceId($UserBalance->getId())
                ->save();
            if(!$r)
                throw new NException('Не удалось сохранить операцию 2', NException::ERROR_FINANCE_OUT);

            if($t !== null) $t->commit();

            return true;
        } catch (\Exception $ex) {
            if($t !== null) $t->rollback();
            \MException::logMongo($ex, 'TransferModer');
        }

        return false;
    }

    public function accept($balance_id)
    {
        $t = null;
        if(Yii::app()->db->currentTransaction === null)
            $t = Yii::app()->db->beginTransaction();
        try {
            $criteria = new \CDbCriteria();
            $criteria->addCondition('id = :id');
            $criteria->params = [':id' => $balance_id];
            /** @var \UserBalanceOutput $UserBalance */
            $UserBalance = \UserBalanceOutput::model()->find($criteria);
            if(!$UserBalance)
                throw new NException('Не удалось найти запись в балансе для реквеста.', NException::ERROR_FINANCE_OUT);
            $UserBalance->setStatus(iStatus::STATUS_FINISH);
            if(!$UserBalance->save())
                throw new NException(
                    'Не удалось обновить перевод.', NException::ERROR_FINANCE_OUT, ['errors' => $UserBalance->getErrors()]);

            $criteria = new \CDbCriteria();
            $criteria->addCondition('user_id = :user_id');
            $criteria->addCondition('price_type = :price_type');
            $criteria->params = [':user_id' => $this->getUserId(), ':price_type' => $this->getPriceType()];
            $ActiveBalance = \UserActiveBalance::model()->find($criteria);
            if(!$ActiveBalance)
                $ActiveBalance = new \UserActiveBalance();
            $ActiveBalance
                ->setUserId($this->getUserId())
                ->setPriceType($this->getPriceType())
                ->addSumOut($UserBalance->getPrice());
            if(!$ActiveBalance->save())
                throw new NException(
                    'Не удалось сохранить активный баланс.', NException::ERROR_FINANCE_OUT, ['errors' => $ActiveBalance->getErrors()]);

            if(!$this->addToGame($this->getUserGameId(), $UserBalance->getPrice(), $this->getBankId()))
                throw new NException("Не удалось пополнить игровые средства", NException::ERROR_FINANCE_OUT);

            if($t !== null) $t->commit();

            return true;
        } catch (\Exception $ex) {
            if($t !== null) $t->rollback();
            \MException::logMongo($ex, 'TransferModer');
            Yii::app()->getAjax()->addErrors($ex->getMessage());
        }

        return false;
    }

    public function cancel($balance_id)
    {
        $t = null;
        if(Yii::app()->getDb()->currentTransaction === null)
            $t = Yii::app()->getDb()->beginTransaction();
        try {
            $criteria = new \CDbCriteria();
            $criteria->addCondition('id = :id');
            $criteria->params = [':id' => $balance_id];
            /** @var \UserBalanceOutput $UserBalance */
            $UserBalance = \UserBalanceOutput::model()->find($criteria);
            if(!$UserBalance)
                throw new NException(
                    'Не удалось найти запись в балансе для реквеста.', NException::ERROR_FINANCE_OUT);
            $UserBalance
                ->setMessage('Запрос на вывод отменен')
                ->setStatus(iStatus::STATUS_CANCEL);
            if(!$UserBalance->save())
                throw new NException(
                    'Не удалось обновить перевод', NException::ERROR_FINANCE_OUT, ['errors' => $UserBalance->getErrors()]);

            $r = $this
                ->addBalance($UserBalance->getPrice())
                ->save();
            if(!$r)
                throw new NException(
                    'Не удалось добавить средства на счет', NException::ERROR_FINANCE_OUT, ['errors' => $this->getUser()->getErrors()]);


            if($t !== null) $t->commit();

            return true;
        } catch (\Exception $ex) {
            if($t !== null) $t->rollback();
            \MException::logMongo($ex, 'TransferModer');
        }

        return false;
    }

    public function decline($balance_id)
    {
        $t = null;
        if(Yii::app()->db->currentTransaction === null)
            $t = Yii::app()->db->beginTransaction();
        try {
            $criteria = new \CDbCriteria();
            $criteria->addCondition('id = :id');
            $criteria->params = [':id' => $balance_id];
            /** @var \UserBalanceOutput $UserBalance */
            $UserBalance = \UserBalanceOutput::model()->find($criteria);
            if(!$UserBalance)
                throw new NException(
                    'Не удалось найти запись в балансе для реквеста.', NException::ERROR_FINANCE_OUT);
            $UserBalance
                ->setMessage('Запрос на вывод отклонен')
                ->setStatus(iStatus::STATUS_DECLINE);
            if(!$UserBalance->save())
                throw new NException(
                    'Не удалось обновить перевод', NException::ERROR_FINANCE_OUT, ['errors' => $UserBalance->getErrors()]);

            $r = $this
                ->addBalance($UserBalance->getPrice())
                ->save();
            if(!$r)
                throw new NException(
                    'Не удалось добавить средства на счет', NException::ERROR_FINANCE_OUT, ['errors' => $this->getUser()->getErrors()]);


            if($t !== null) $t->commit();

            return true;
        } catch (\Exception $ex) {
            if($t !== null) $t->rollback();
            \MException::logMongo($ex, 'TransferModer');
        }

        return false;
    }
}