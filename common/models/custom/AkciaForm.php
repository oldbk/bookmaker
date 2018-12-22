<?php
/**
 * Created by PhpStorm.
 */

class AkciaForm extends CFormModel
{
    public $akcia_id;
    public $user_id;
    public $price_type;
    public $price;

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param mixed $user_id
     * @return $this
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPriceType()
    {
        return $this->price_type;
    }

    /**
     * @param mixed $price_type
     * @return $this
     */
    public function setPriceType($price_type)
    {
        $this->price_type = $price_type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     * @return $this
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAkciaId()
    {
        return $this->akcia_id;
    }

    /**
     * @param mixed $akcia_id
     * @return $this
     */
    public function setAkciaId($akcia_id)
    {
        $this->akcia_id = $akcia_id;
        return $this;
    }
}