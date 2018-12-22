<?php
namespace common\factories\transfer\traits;
use common\interfaces\iPrice;
use common\singletons\prices\PricesKR;
use Yii;

/**
 * Created by PhpStorm.
 */

trait tKR
{
    /**
     * @return string
     */
    protected function getStrangeValue()
    {
        return PricesKR::init()->getStrangeOutput();
    }

    /**
     * @return int
     */
    protected function getPriceType()
    {
        return iPrice::TYPE_KR;
    }

    /**
     * @param $price
     * @return \User
     */
    protected function addBalance($price)
    {
        return $this->getUser()->addKrBalance($price);
    }

    /**
     * @param $price
     * @return \User
     */
    protected function takeBalance($price)
    {
        return $this->getUser()->takeKrBalance($price);
    }

    /**
     * @return float
     */
    protected function getBalance()
    {
        return $this->getUser()->getKrBalance();
    }

    protected function takeFromGame($game_id, $price, $bank_id = null)
    {
        return Yii::app()->getOldbk()->takeKr($game_id, $price);
    }

    protected function addToGame($game_id, $price, $bank_id = null)
    {
        return Yii::app()->getOldbk()->putKr($game_id, $price);
    }

    /**
     * @return \User
     */
    abstract public function getUser();

    /**
     * @return mixed
     */
    abstract public function getUserGameId();
}