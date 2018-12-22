<?php
namespace common\factories\transfer\traits;
use common\interfaces\iPrice;
use common\singletons\prices\PricesGold;
use Yii;

/**
 * Created by PhpStorm.
 */

trait tGold
{
    protected function getStrangeValue()
    {
        return PricesGold::init()->getStrangeOutput();
    }

    protected function getPriceType()
    {
        return iPrice::TYPE_GOLD;
    }

    protected function addBalance($price)
    {
        return $this->getUser()->addGoldBalance($price);
    }

    protected function takeBalance($price)
    {
        return $this->getUser()->takeGoldBalance($price);
    }

    protected function getBalance()
    {
       return $this->getUser()->getGoldBalance();
    }

    protected function addToGame($game_id, $price, $bank_id = null)
    {
        return Yii::app()->getOldbk()->putGold($game_id, $price, $bank_id);
    }

    protected function takeFromGame($game_id, $price, $bank_id = null)
    {
        return Yii::app()->getOldbk()->takeGold($game_id, $price, $bank_id);
    }

    /**
     * @return \User
     */
    abstract public function getUser();
}