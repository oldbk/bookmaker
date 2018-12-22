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

abstract class BaseBalance extends Base
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
     * @param $price
     * @param null $msg
     * @return bool
     * @throws \CDbException
     */
    public function take($price, $msg = null)
    {
        /** @var float $price */
        $price = Convert::getMoneyFormat($price);
        $t = null;
        if(Yii::app()->db->currentTransaction === null)
            $t = Yii::app()->db->beginTransaction();
        try {
            //создаем запись о снятии с баланса
            $UserBalance = new \UserBalanceTake();
            $UserBalance
                ->setUserId($this->getUserId())
                ->setPrice($price)
                ->setPriceType($this->getPriceType())
                ->setOperationType(iPrice::OPERATION_TYPE_TAKE)
                ->setStatus(iStatus::STATUS_FINISH)
                ->setBalanceBefore($this->getBalance())
                ->setMessage($msg);
            if(!$UserBalance->validate())
                throw new NException(
                    'Не удалось сохранить историю по операции', NException::ERROR_FINANCE_OUT, ['errors' => $UserBalance->getErrors()]);

            $r = $this
                ->takeBalance($price)
                ->validate();
            if(!$r)
                throw new NException(
                    'Не удалось добавить средства на счет', NException::ERROR_FINANCE_OUT, ['errors' => $this->getUser()->getErrors()]);

            $UserBalance->setBalanceAfter($this->getBalance());
            if(!$this->getUser()->save() || !$UserBalance->save())
                throw new NException('Не удалось сохранить операцию', NException::ERROR_FINANCE_OUT);

            if($t !== null) $t->commit();

            return true;
        } catch (\Exception $ex) {
            if($t !== null) $t->rollback();
            \MException::logMongo($ex, 'TransferBalance');
        }

        return false;
    }

    /**
     * @param $price
     * @param null $msg
     * @return bool
     * @throws \CDbException
     */
    public function add($price, $msg = null)
    {
        /** @var float $price */
        $price = Convert::getMoneyFormat($price);
        $t = null;
        if(Yii::app()->db->currentTransaction === null)
            $t = Yii::app()->db->beginTransaction();
        try {
            //создаем запись о зачислении на баланс
            $UserBalance = new \UserBalanceAdd();
            $UserBalance
                ->setUserId($this->getUserId())
                ->setPrice($price)
                ->setPriceType($this->getPriceType())
                ->setOperationType(iPrice::OPERATION_TYPE_ADD)
                ->setStatus(iStatus::STATUS_FINISH)
                ->setMessage($msg)
                ->setBalanceBefore($this->getBalance());
            if(!$UserBalance->validate())
                throw new NException(
                    'Не удалось сохранить историю по операции.', NException::ERROR_FINANCE_IN, ['errors' => $UserBalance->getErrors()]);

            $r = $this
                ->addBalance($price)
                ->validate();
            if(!$r)
                throw new NException(
                    'Не удалось добавить средства на баланс', NException::ERROR_FINANCE_IN, ['errors' => $this->getUser()->getErrors()]);

            $UserBalance->setBalanceAfter($this->getBalance());
            if(!$this->getUser()->save() || !$UserBalance->save())
                throw new NException('Не удалось сохранить операцию', NException::ERROR_FINANCE_IN);

            if($t !== null) $t->commit();

            return true;
        } catch (\Exception $ex) {
            if($t !== null) $t->rollback();
            \MException::logMongo($ex, 'TransferBalance');
        }

        return false;
    }
}