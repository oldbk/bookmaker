<?php
/**
 * Created by PhpStorm.
 */

namespace common\components\oldbk;


class Bank extends Base
{
    public $id = null;
    public $ekr = 0.00;
    public $cr = 0.00;

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
    public function getEkr()
    {
        return $this->ekr;
    }

    /**
     * @param float $ekr
     * @return $this
     */
    public function setEkr($ekr)
    {
        $this->ekr = $ekr;
        return $this;
    }

    /**
     * @return float
     */
    public function getCr()
    {
        return $this->cr;
    }

    /**
     * @param float $cr
     * @return $this
     */
    public function setCr($cr)
    {
        $this->cr = $cr;
        return $this;
    }
}