<?php

Yii::import('common.models._base.BaseUserActiveBalance');

class UserActiveBalance extends BaseUserActiveBalance
{
    /**
     * @param string $className
     * @return UserActiveBalance
     */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

    public function behaviors()
    {
        return [
            // Password behavior strategy
            'MTimestampBehavior' => [
                'class' => 'common\extensions\behaviors\MTimestampBehavior',
                'createAttribute' => 'create_at',
                'updateAttribute' => 'update_at',
                'setUpdateOnCreate' => true
            ]
        ];
    }

    public function relations() {
        return [
            'user' => [
                self::BELONGS_TO,
                'User',
                'user_id',
                'joinType' => 'inner join'
            ],
        ];
    }

    public function beforeValidate()
    {
        $r = parent::beforeValidate();

        $this->setActiveDiff(\common\helpers\Convert::getMoneyFormat($this->sum_in - $this->sum_out));

        return $r;
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
    public function getPriceType()
    {
        return $this->price_type;
    }

    /**
     * @param int $price_type
     * @return $this
     */
    public function setPriceType($price_type)
    {
        $this->price_type = $price_type;
        return $this;
    }

    /**
     * @return string
     */
    public function getSumIn()
    {
        return $this->sum_in;
    }

    /**
     * @param string $sum_in
     * @return $this
     */
    public function setSumIn($sum_in)
    {
        $this->sum_in = $sum_in;
        return $this;
    }

    /**
     * @param $price
     * @return $this
     */
    public function addSumIn($price)
    {
        $sum = ($this->sum_in * 100 + $price * 100) / 100;
        $this->sum_in = \common\helpers\Convert::getMoneyFormat($sum);
        return $this;
    }

    public function takeSumIn($price)
    {
        $sum = ($this->sum_in * 100 - $price * 100) / 100;
        $this->sum_in = \common\helpers\Convert::getMoneyFormat($sum);
        return $this;
    }

    /**
     * @return string
     */
    public function getSumOut()
    {
        return $this->sum_out;
    }

    /**
     * @param string $sum_out
     * @return $this
     */
    public function setSumOut($sum_out)
    {
        $this->sum_out = $sum_out;
        return $this;
    }

    /**
     * @param $price
     * @return $this
     */
    public function addSumOut($price)
    {
        $sum = ($this->sum_out * 100 + $price * 100) / 100;
        $this->sum_out = \common\helpers\Convert::getMoneyFormat($sum);
        return $this;
    }

    public function takeSumOut($price)
    {
        $sum = ($this->sum_out * 100 - $price * 100) / 100;
        $this->sum_out = \common\helpers\Convert::getMoneyFormat($sum);
        return $this;
    }

    /**
     * @return string
     */
    public function getActiveDiff()
    {
        return $this->active_diff;
    }

    /**
     * @param string $active_diff
     * @return $this
     */
    public function setActiveDiff($active_diff)
    {
        $this->active_diff = $active_diff;
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