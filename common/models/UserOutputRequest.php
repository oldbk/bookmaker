<?php

Yii::import('common.models._base.BaseUserOutputRequest');

/**
 * Class UserOutputRequest
 *
 * @property string message
 * @property int bankid
 * @property int balance_id
 *
 * @property User $user
 */
class UserOutputRequest extends BaseUserOutputRequest
{
    const STATUS_NEW        = 0;
    const STATUS_ACCEPT     = 1;
    const STATUS_DECLINE    = 2;

    /**
     * @param string $className
     * @return UserOutputRequest
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

    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'Логин'),
            'price' => Yii::t('app', 'Сумма'),
            'price_type' => Yii::t('app', 'Price Type'),
            'status' => Yii::t('app', 'Status'),
            'moderator_id' => Yii::t('app', 'Moderator'),
            'update_at' => Yii::t('app', 'Update At'),
            'create_at' => Yii::t('app', 'Дата'),
        ];
    }

    public function relations() {
        return [
            'user' => [
                self::BELONGS_TO, 'User', 'user_id', 'joinType' => 'inner join'
            ]
        ];
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
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
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return int
     */
    public function getModeratorId()
    {
        return $this->moderator_id;
    }

    /**
     * @param int $moderator_id
     * @return $this
     */
    public function setModeratorId($moderator_id)
    {
        $this->moderator_id = $moderator_id;
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

    /**
     * @return int
     */
    public function getBankid()
    {
        return $this->bankid;
    }

    /**
     * @param int $bankid
     * @return $this
     */
    public function setBankid($bankid)
    {
        $this->bankid = $bankid;
        return $this;
    }

    /**
     * @return int
     */
    public function getBalanceId()
    {
        return $this->balance_id;
    }

    /**
     * @param int $balance_id
     * @return $this
     */
    public function setBalanceId($balance_id)
    {
        $this->balance_id = $balance_id;
        return $this;
    }
}