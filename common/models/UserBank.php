<?php

Yii::import('common.models._base.BaseUserBank');

class UserBank extends BaseUserBank
{
    /**
     * @param string $className
     * @return UserBank
     */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     * @return $this
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getBankNumber()
    {
        return $this->bank_number;
    }

    /**
     * @param int $bank_number
     * @return $this
     */
    public function setBankNumber($bank_number)
    {
        $this->bank_number = $bank_number;
        return $this;
    }

    /**
     * @return string
     */
    public function getBankPass()
    {
        return $this->bank_pass;
    }

    /**
     * @param string $bank_pass
     * @return $this
     */
    public function setBankPass($bank_pass)
    {
        $this->bank_pass = $bank_pass;
        return $this;
    }

    /**
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param string $price
     * @return $this
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return int
     */
    public function getUpdateAt()
    {
        return $this->update_at;
    }

    /**
     * @param int $update_at
     * @return $this
     */
    public function setUpdateAt($update_at)
    {
        $this->update_at = $update_at;
        return $this;
    }

    /**
     * @return int
     */
    public function getCreateAt()
    {
        return $this->create_at;
    }

    /**
     * @param int $create_at
     * @return $this
     */
    public function setCreateAt($create_at)
    {
        $this->create_at = $create_at;
        return $this;
    }
}