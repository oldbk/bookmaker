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

abstract class BaseBetting extends Base
{
    /**
     * @return float
     */
    abstract protected function getBalance();

    /**
     * @param $price
     * @return \User
     */
    abstract protected function takeBalance($price);

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
        $price = Convert::getMoneyFormat($price);
        $t = null;
        if(Yii::app()->db->currentTransaction === null)
            $t = Yii::app()->db->beginTransaction();
        try {

            if((($this->getBalance() * 100 - $price * 100)/100) < 0) {
                $this->error_msg = 'Недостаточно средств на активном счете';
                throw new NException('Недостаточно средств на активном счете', NException::ERROR_BET, [
                    'user_id' => $this->getUser()->getId(),
                    'user_login' => $this->getUser()->getLogin(),
                    'balance' => $this->getBalance(),
                    'price' => $price,
                    'balance_type' => $this->getPriceType()
                ]);
            }

            $UserBalance = new \UserBalanceBet();
            $UserBalance
                ->setUserId($this->getUser()->getId())
                ->setBetGroupId($this->getBetGroup()->getId())
                ->setPrice($price)
                ->setPriceType($this->getPriceType())
                ->setOperationType(iPrice::OPERATION_TYPE_TAKE)
                ->setStatus(iStatus::STATUS_FINISH)
                ->setMessage($msg)
                ->setBalanceBefore($this->getBalance())
                ->setUserGroupNumber($this->getBetGroup()->getUserGroupNumber());
            if(!$UserBalance->validate()) {
                $this->setErrorMsg('Не удалось сохранить историю по операции');
                throw new NException('Не удалось сохранить историю по операции.', NException::ERROR_BET, ['errors' => $UserBalance->getErrors()]);
            }

            $r = $this
                ->takeBalance($price)
                ->validate();
            if(!$r) {
                $this->setErrorMsg('Не удалось отнять средства по операции');
                throw new NException('Не удалось отнять средства по операции.', NException::ERROR_BET, ['errors' => $this->getUser()->getErrors()]);
            }

            $UserBalance->setBalanceAfter($this->getBalance());
            if(!$this->getUser()->save() || !$UserBalance->save())
                throw new NException('Не удалось сохранить операцию', NException::ERROR_BET);

            if($t !== null) $t->commit();

            return true;
        } catch (\Exception $ex) {
            if($t !== null) $t->rollback();
            \MException::logMongo($ex, 'TransferBetting');
        }

        return false;
    }
}