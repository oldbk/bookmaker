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

abstract class BasePayment extends Base
{
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
     * @param $user
     * @param \BettingGroup $betGroup
     */
    public function __construct(&$user, $betGroup)
    {
        parent::__construct($user);
        $this->setBetGroup($betGroup);
    }

    /**
     * @param $price
     * @param null $msg
     * @return bool
     * @throws \CDbException
     */
    public function run($price, $msg = null)
    {
        /** @var float $price */
        $price = Convert::getMoneyFormat($price);
        $t = null;
        if(Yii::app()->db->currentTransaction === null)
            $t = Yii::app()->db->beginTransaction();
        try {
            $UserBalance = new \UserBalancePayment();
            $UserBalance
                ->setUserId($this->getUser()->getId())
                ->setBetGroupId($this->getBetGroup()->getId())
                ->setPrice($price)
                ->setPriceType($this->getPriceType())
                ->setOperationType(iPrice::OPERATION_TYPE_ADD)
                ->setMessage($msg)
                ->setStatus(iStatus::STATUS_FINISH)
                ->setBalanceBefore($this->getBalance())
                ->setUserGroupNumber($this->getBetGroup()->getUserGroupNumber());
            if(!$UserBalance->validate())
                throw new NException('Не удалось сохранить историю по операции.', NException::ERROR_PAYMENT, ['errors' => $UserBalance->getErrors()]);

            $r = $this
                ->addBalance($price)
                ->validate();
            if(!$r)
                throw new NException('Не удалось добавить средства по операции.', NException::ERROR_PAYMENT, ['errors' => $this->getUser()->getErrors()]);

            $UserBalance->setBalanceAfter($this->getBalance());
            if(!$this->getUser()->save() || !$UserBalance->save())
                throw new NException('Не удалось сохранить операцию', NException::ERROR_PAYMENT);

            if($t !== null) $t->commit();

            return true;
        } catch (\Exception $ex) {
            if($t !== null) $t->rollback();
            \MException::logMongo($ex, 'TransferPayment');
        }

        return false;
    }
}