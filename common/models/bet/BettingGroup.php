<?php

Yii::import('common.models._base.BaseBettingGroup');

/**
 * Class BettingGroup
 *
 * @property User $user
 *
 * @property boolean is_refund
 * @property int user_group_number
 */
class BettingGroup extends BaseBettingGroup implements \common\interfaces\iPrice, \common\interfaces\iStatus
{
	const TYPE_ORDINAR = 0;
	const TYPE_EXPRESS = 1;

    public $sum = 0;
    public $sum_bet = 0;
    public $sum_payment = 0;

	/**
	 * @param string $className
	 * @return BettingGroup
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
				'createDatetimeAttribute' => 'create_at_datetime',
				'setUpdateOnCreate' => true
			]
		];
	}

	public function scopes()
	{
		$t = $this->getTableAlias(false, false);
		return [
			'own' => [
				'condition' => "`{$t}`.user_id = :{$t}user_id",
				'params' => [":{$t}user_id" => Yii::app()->getUser()->getId()]
			],
			'enable' => [
				'condition' => "`{$t}`.status = :{$t}status",
				'params' => [":{$t}status" => self::STATUS_ENABLE]
			],
            'error' => [
                'condition' => "`{$t}`.status = :{$t}status_error",
                'params' => [":{$t}status_error" => self::STATUS_ERROR]
            ]
		];
	}

	public function relations() {
		return [
			'userBetting' => [
                self::HAS_MANY,
                'UserBetting',
                'bet_group_id',
                'joinType' => 'inner join',
            ],
            'userBettingOne' => [
                self::HAS_ONE,
                'UserBetting',
                'bet_group_id',
                'joinType' => 'inner join',
            ],
            'finishedAllBet' => [
                self::HAS_MANY,
                'UserBetting',
                'bet_group_id',
                'joinType' => 'left join',
                'on' => 'finishedAllBet.status != :finishedAllBet_finish',
                'condition' => '`finishedAllBet`.id is NULL',
                'params' => [':finishedAllBet_finish' => \common\interfaces\iStatus::STATUS_FINISH],
                'select' => []
            ],
            'haveEvent' => [
                self::HAS_MANY,
                'UserBetting',
                'bet_group_id',
                'joinType' => 'left join',
                'select' => []
            ],
            'notLossEvent' => [
                self::HAS_MANY,
                'UserBetting',
                'bet_group_id',
                'joinType' => 'left join',
                'on' => 'notLossEvent.result_status = :notLossEvent',
                'condition' => '`notLossEvent`.id is NULL',
                'params' => [':notLossEvent' => \common\interfaces\iStatus::RESULT_LOSS],
                'select' => []
            ],
            'user' => [
                self::BELONGS_TO,
                'User',
                'user_id',
                'joinType' => 'inner join'
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
	public function getBetType()
	{
		return $this->bet_type;
	}

	/**
	 * @param int $bet_type
	 * @return $this
	 */
	public function setBetType($bet_type)
	{
		$this->bet_type = $bet_type;
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
	 * @return string
	 */
	public function getRatioValue()
	{
		return $this->ratio_value;
	}

	/**
	 * @param string $ratio_value
	 * @return $this
	 */
	public function setRatioValue($ratio_value)
	{
		$this->ratio_value = $ratio_value;
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
	public function getResultStatus()
	{
		return $this->result_status;
	}

	/**
	 * @param int $result_status
	 * @return $this
	 */
	public function setResultStatus($result_status)
	{
		$this->result_status = $result_status;
		return $this;
	}

	/**
	 * @return float
	 */
	public function getPaymentSum()
	{
		return $this->payment_sum;
	}

	/**
	 * @param string $payment_sum
	 * @return $this
	 */
	public function setPaymentSum($payment_sum)
	{
		$this->payment_sum = $payment_sum;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getRefundAt()
	{
		return $this->refund_at;
	}

	/**
	 * @param int $refund_at
	 * @return $this
	 */
	public function setRefundAt($refund_at)
	{
		$this->refund_at = $refund_at;
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
	 * @return string
	 */
	public function getCreateAtDatetime()
	{
		return $this->create_at_datetime;
	}

	/**
	 * @param string $create_at_datetime
	 * @return $this
	 */
	public function setCreateAtDatetime($create_at_datetime)
	{
		$this->create_at_datetime = $create_at_datetime;
		return $this;
	}

	public function createAction()
	{
		return $this->setUserId(Yii::app()->getUser()->getId())->save();
	}

    /**
     * @return float
     */
	public function getSumWin()
	{
		return \common\helpers\Convert::getMoneyFormat($this->ratio_value * $this->price);
	}

    /**
     * @param $isWin
     * @return $this
     *
     * @deprecated
     */
	public function setFinishStatus($isWin)
	{
        $this->setResultStatus($isWin);

        return $this;
	}

    /**
     * @return int
     *
     * @deprecated use getResultStatus()
     */
    public function getFinishStatus()
    {
        return (int)$this->getResultStatus();
    }

    /**
     * @return boolean
     */
    public function isRefund()
    {
        return $this->is_refund;
    }

    /**
     * @param boolean $is_refund
     * @return $this
     */
    public function setIsRefund($is_refund)
    {
        $this->is_refund = $is_refund;
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