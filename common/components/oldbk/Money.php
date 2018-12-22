<?php
/**
 * Created by PhpStorm.
 */

namespace common\components\oldbk;


class Money extends Base
{
    public $id = null;
    public $money = 0.00;

    /**
     * @return null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param null $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return float
     */
    public function getMoney()
    {
        return $this->money;
    }

    /**
     * @param float $money
     * @return $this
     */
    public function setMoney($money)
    {
        $this->money = $money;
        return $this;
    }
}