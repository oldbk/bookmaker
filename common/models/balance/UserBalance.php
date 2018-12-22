<?php

Yii::import('common.models._base.BaseUserBalance');

/**
 * Class UserBalance
 *
 * @property float price
 * @property string moderation_message
 * @property int moderation_status
 * @property int akcia_id
 * @property Akcii akcii
 * @property User user
 * @property BettingGroup betGroup
 *
 *
 * @property boolean is_strange
 * @property boolean is_moder
 * @property int status
 * @property string moder_text
 * @property int bank_id
 * @property float balance_after
 * @property float balance_before
 * @property int user_group_number
 */
class UserBalance extends BaseUserBalance implements \common\interfaces\iPrice
{
    const BALANCE_TYPE_INPUT    = 0;
    const BALANCE_TYPE_OUTPUT   = 1;
    const BALANCE_TYPE_BET      = 2;
    const BALANCE_TYPE_PAYMENT  = 3;
    const BALANCE_TYPE_TAKE     = 4;
    const BALANCE_TYPE_ADD      = 5;

    const MODERATION_STATUS_NONE    = 0;
    const MODERATION_STATUS_PROCESS = 1;
    const MODERATION_STATUS_ACCEPT  = 2;
    const MODERATION_STATUS_DECLINE = 3;

	public $sum = 0;

	/**
	 * @param string $className
	 * @return UserBalance
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
            'akcii' => [
                self::HAS_ONE,
                'Akcii',
                ['id' => 'akcia_id'],
                'joinType' => 'left join'
            ],
            'user' => [
                self::BELONGS_TO,
                'User',
                'user_id',
                'joinType' => 'inner join'
            ],
            'request' => [
                self::HAS_ONE,
                'UserOutputRequest',
                'balance_id',
                'joinType' => 'left join'
            ],
            'betGroup' => [
                self::HAS_ONE,
                'BettingGroup',
                ['id' => 'bet_group_id'],
            ],
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
	public function getOperationType()
	{
		return $this->operation_type;
	}

	/**
	 * @param int $operation_type
	 * @return $this
	 */
	public function setOperationType($operation_type)
	{
		$this->operation_type = $operation_type;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getMessage()
	{
		return $this->message;
	}

	/**
	 * @param string $message
	 * @return $this
	 */
	public function setMessage($message)
	{
		$this->message = $message;
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
	 * @return float
	 */
	public function getPrice()
	{
		return $this->price;
	}

	/**
	 * @param float $price
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
    public function getBetGroupId()
    {
        return $this->bet_group_id;
    }

    /**
     * @param int $bet_group_id
     * @return $this
     */
    public function setBetGroupId($bet_group_id)
    {
        $this->bet_group_id = $bet_group_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getModerationMessage()
    {
        return $this->moderation_message;
    }

    /**
     * @param string $moderation_message
     * @return $this
     */
    public function setModerationMessage($moderation_message)
    {
        $this->moderation_message = $moderation_message;
        return $this;
    }

    /**
     * @return int
     */
    public function getModerationStatus()
    {
        return $this->moderation_status;
    }

    /**
     * @param int $moderation_status
     * @return $this
     */
    public function setModerationStatus($moderation_status)
    {
        $this->moderation_status = $moderation_status;
        return $this;
    }

    /**
     * @return int
     */
    public function getAkciaId()
    {
        return $this->akcia_id;
    }

    /**
     * @param int $akcia_id
     * @return $this
     */
    public function setAkciaId($akcia_id)
    {
        $this->akcia_id = $akcia_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getSum()
    {
        return $this->sum;
    }

    /**
     * @param int $sum
     * @return $this
     */
    public function setSum($sum)
    {
        $this->sum = $sum;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isStrange()
    {
        return $this->is_strange;
    }

    /**
     * @param boolean $is_strange
     * @return $this
     */
    public function setIsStrange($is_strange)
    {
        $this->is_strange = $is_strange;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isModer()
    {
        return $this->is_moder;
    }

    /**
     * @param boolean $is_moder
     * @return $this
     */
    public function setIsModer($is_moder)
    {
        $this->is_moder = $is_moder;
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
     * @return string
     */
    public function getModerText()
    {
        return $this->moder_text;
    }

    /**
     * @param string $moder_text
     * @return $this
     */
    public function setModerText($moder_text)
    {
        $this->moder_text = $moder_text;
        return $this;
    }

    /**
     * @return int
     */
    public function getBankId()
    {
        return $this->bank_id;
    }

    /**
     * @param int $bank_id
     * @return $this
     */
    public function setBankId($bank_id)
    {
        $this->bank_id = $bank_id;
        return $this;
    }

    /**
     * @return float
     */
    public function getBalanceAfter()
    {
        return $this->balance_after;
    }

    /**
     * @param float $balance_after
     * @return $this
     */
    public function setBalanceAfter($balance_after)
    {
        $this->balance_after = $balance_after;
        return $this;
    }

    /**
     * @return float
     */
    public function getBalanceBefore()
    {
        return $this->balance_before;
    }

    /**
     * @param float $balance_before
     * @return $this
     */
    public function setBalanceBefore($balance_before)
    {
        $this->balance_before = $balance_before;
        return $this;
    }

    /**
     * @return int
     */
    public function getPaymentType()
    {
        return $this->payment_type;
    }

    /**
     * @param int $payment_type
     * @return $this
     */
    public function setPaymentType($payment_type)
    {
        $this->payment_type = $payment_type;
        return $this;
    }

    /**
     * @return int
     */
    public function getUserGroupNumber()
    {
        return $this->user_group_number;
    }

    /**
     * @param int $user_group_number
     * @return $this
     */
    public function setUserGroupNumber($user_group_number)
    {
        $this->user_group_number = $user_group_number;
        return $this;
    }
}