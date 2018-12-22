<?php
namespace common\factories\transfer\traits;
use common\interfaces\iPrice;
use common\singletons\prices\PricesEKR;
use Yii;

/**
 * Created by PhpStorm.
 */

trait tEKR
{
    protected function getStrangeValue()
    {
        return PricesEKR::init()->getStrangeOutput();
    }

    protected function getPriceType()
    {
        return iPrice::TYPE_EKR;
    }

    protected function addBalance($price)
    {
        return $this->getUser()->addEkrBalance($price);
    }

    protected function takeBalance($price)
    {
        return $this->getUser()->takeEkrBalance($price);
    }

    protected function getBalance()
    {
       return $this->getUser()->getEkrBalance();
    }

    protected function addToGame($game_id, $price, $bank_id = null)
    {
        return Yii::app()->getOldbk()->putEkr($game_id, $price, $bank_id);
    }

    protected function takeFromGame($game_id, $price, $bank_id = null)
    {
        return Yii::app()->getOldbk()->takeEkr($game_id, $price, $bank_id);
    }

    /**
     * @return \User
     */
    abstract public function getUser();
}