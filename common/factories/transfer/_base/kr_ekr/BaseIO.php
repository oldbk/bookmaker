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

abstract class BaseIO extends Base
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
     * @param $game_id
     * @param $price
     * @param $bank_id
     * @return boolean
     */
    abstract protected function takeFromGame($game_id, $price, $bank_id = null);

    /**
     * @param $game_id
     * @param $price
     * @param $bank_id
     * @return boolean
     */
    abstract protected function addToGame($game_id, $price, $bank_id = null);

    /**
     * @return float
     */
    abstract protected function getBalance();

    /**
     * @param $price
     * @param $msg
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
            //Правим активный баланс
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
                ->addSumOut($price);
            if(!$ActiveBalance->save())
                throw new NException(
                    'Не удалось сохранить активный баланс.', NException::ERROR_FINANCE_OUT, ['errors' => $ActiveBalance->getErrors()]);

            //создаем запись о выводе
            $UserBalance = new \UserBalanceOutput();
            if($this->getStrangeValue() > 0 && $price >= $this->getStrangeValue())
                $UserBalance->setIsStrange(1);
            $UserBalance
                ->setUserId($this->getUserId())
                ->setPrice($price)
                ->setPriceType($this->getPriceType())
                ->setOperationType(iPrice::OPERATION_TYPE_TAKE)
                ->setStatus(iStatus::STATUS_FINISH)
                ->setMessage($msg)
                ->setBankId($this->getBankId())
                ->setBalanceBefore($this->getBalance());
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

            if(!$this->addToGame($this->getUserGameId(), $price, $this->getBankId()))
                throw new NException("Не удалось пополнить игровые средства", NException::ERROR_FINANCE_OUT);

            if($t !== null) $t->commit();

            return true;
        } catch (\Exception $ex) {
            if($t !== null) $t->rollback();
            \MException::logMongo($ex, 'TransferIO');
        }

        return false;
    }

    /**
     * @param $price
     * @param $msg
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
                ->addSumIn($price);
            if(!$ActiveBalance->save())
                throw new NException(
                    'Не удалось сохранить активный баланс', NException::ERROR_FINANCE_IN, ['errors' => $ActiveBalance->getErrors()]);

            $UserBalance = new \UserBalanceInput();
            $UserBalance
                ->setUserId($this->getUserId())
                ->setPrice($price)
                ->setPriceType($this->getPriceType())
                ->setOperationType(iPrice::OPERATION_TYPE_ADD)
                ->setStatus(iStatus::STATUS_FINISH)
                ->setBankId($this->getBankId())
                ->setMessage($msg)
                ->setBalanceBefore($this->getBalance());
            if(!$UserBalance->validate())
                throw new NException(
                    'Не удалось сохранить историю по операции', NException::ERROR_FINANCE_IN, ['errors' => $UserBalance->getErrors()]);

            $r = $this
                ->addBalance($price)
                ->validate();
            if(!$r)
                throw new NException(
                    'Не удалось добавить средства на счет', NException::ERROR_FINANCE_IN, ['errors' => $this->getUser()->getErrors()]);

            $UserBalance->setBalanceAfter($this->getBalance());
            if(!$this->getUser()->save() || !$UserBalance->save())
                throw new NException('Не удалось сохранить операцию', NException::ERROR_FINANCE_IN);

            if(!$this->takeFromGame($this->getUserGameId(), $price, $this->getBankId()))
                throw new NException("Не снять игровые средства", NException::ERROR_FINANCE_IN);

            if($t !== null) $t->commit();

            return true;
        } catch (\Exception $ex) {
            if($t !== null) $t->rollback();
            \MException::logMongo($ex, 'TransferIO');
        }

        return false;
    }
}