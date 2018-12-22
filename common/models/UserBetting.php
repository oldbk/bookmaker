<?php

Yii::import('common.models._base.BaseUserBetting');

/**
 * Class UserBetting
 *
 * @property int date_int
 * @property int dop_ratio
 *
 * @property SportEventRatio[] eventRatio
 * @property SportEventRatio betRatio
 * @property SportEvent eventOriginal
 * @property User user
 * @property SportEventResult[] eventResult
 */
class UserBetting extends BaseUserBetting implements \common\interfaces\iPrice, \common\interfaces\iStatus
{
	public $sum = 0;

	/**
	 * @param string $className
	 * @return UserBetting
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
			'betGroup' => [
				self::BELONGS_TO,
				'BettingGroup',
				'bet_group_id',
				'joinType' => 'inner join'
			],
			'user' => [
				self::BELONGS_TO,
				'User',
				'user_id',
				'joinType' => 'inner join'
			],
            'eventOriginal' => [
				self::HAS_ONE,
				'SportEvent',
				['id' => 'event_id'],
				'joinType' => 'inner join'
			],
			'eventRatio' => [
				self::HAS_MANY,
				'SportEventRatio',
				['event_id' => 'event_id', '_v' => '_v'],
				'joinType' => 'inner join'
			],
			'betRatio' => [
				self::HAS_ONE,
				'SportEventRatio',
				['event_id' => 'event_id', '_v' => '_v', 'type' => 'ratio_type'],
				'joinType' => 'inner join'
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
	public function getEventId()
	{
		return $this->event_id;
	}

	/**
	 * @param int $event_id
	 * @return $this
	 */
	public function setEventId($event_id)
	{
		$this->event_id = $event_id;
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
	 * @return float
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
	public function getRatioType()
	{
		return $this->ratio_type;
	}

	/**
	 * @param string $ratio_type
	 * @return $this
	 */
	public function setRatioType($ratio_type)
	{
		$this->ratio_type = $ratio_type;
		return $this;
	}

	/**
	 * @return float
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
	 * @return int
	 */
	public function getV()
	{
		return $this->_v;
	}

	/**
	 * @param int $v
	 * @return $this
	 */
	public function setV($v)
	{
		$this->_v = $v;
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

	public function createAction()
	{
		return $this->setUserId(Yii::app()->getUser()->getId())->save();
	}

	public function getCssClassResult()
	{
		if($this->getResultStatus() == self::RESULT_WIN)
			return 'win';
		if($this->getResultStatus() == self::RESULT_LOSS)
			return 'loss';

		return null;
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
		return $this->getResultStatus();
	}

    /**
     * @return int
     */
    public function getDateInt()
    {
        return $this->date_int;
    }

    /**
     * @param int $date_int
     * @return $this
     */
    public function setDateInt($date_int)
    {
        $this->date_int = $date_int;
        return $this;
    }

    /**
     * @return int
     */
    public function getDopRatio()
    {
        return $this->dop_ratio;
    }

    /**
     * @param int $dop_ratio
     * @return $this
     */
    public function setDopRatio($dop_ratio)
    {
        $this->dop_ratio = $dop_ratio;
        return $this;
    }
}