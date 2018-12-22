<?php

Yii::import('common.models._base.BaseSettings');

/**
 * Class Settings
 *
 * @property float kr_bank
 * @property int min_level
 * @property boolean enable_autoapprove
 *
 * @property object onAfterChange
 */
class Settings extends BaseSettings implements \common\interfaces\iPrice, \common\interfaces\iAdminLog
{
	/**
	 * @param string $className
	 * @return Settings
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
			'min_time_decline' => Yii::t('app', 'Время отказа'),
			'max_percent_decline' => Yii::t('app', 'Максимальная комиссия'),
			'is_daily_limit' => Yii::t('app', 'Включить дневной лимит по уровням?'),
			'deadline_notify_event' => Yii::t('app', 'Время оповещения, если нет результатов?'),
			'min_level' => Yii::t('app', 'Минимальный уровень для ставок в кр\екр?'),
			'enable_autoapprove' => Yii::t('app', 'Включить авто включение событий?'),
		];
	}

    public function rules() {
        return [
            ['min_time_decline, is_daily_limit, enable_autoapprove, deadline_notify_event, update_at, create_at, min_level', 'numerical', 'integerOnly'=>true],
            ['max_percent_decline', 'length', 'max'=>19],
            ['min_level, min_time_decline, max_percent_decline, is_daily_limit, enable_autoapprove, deadline_notify_event, update_at, create_at', 'default', 'setOnEmpty' => true, 'value' => null],
            ['min_level, id, min_time_decline, max_percent_decline, is_daily_limit, enable_autoapprove, deadline_notify_event, update_at, create_at', 'safe', 'on'=>'search'],
        ];
    }

    public function afterFind()
    {
        parent::afterFind();

		foreach ($this->getCompareList() as $field)
			$this->_old_attributes[$field] = $this->getAttribute($field);
    }

	private $_old_attributes = [];
    public function getOldAttributes()
    {
        return $this->_old_attributes;
    }

    public function getNewAttributes()
    {
        $return = [];
        foreach ($this->getCompareList() as $field)
            $return[$field] = $this->getAttribute($field);

        return $return;
    }

    public function getCompareList()
    {
        return [
			'min_time_decline',
			'max_percent_decline',
			'is_daily_limit',
			'deadline_notify_event',
			'min_level'
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
	public function getMinTimeDecline()
	{
		return $this->min_time_decline;
	}

	/**
	 * @param int $min_time_decline
	 * @return $this
	 */
	public function setMinTimeDecline($min_time_decline)
	{
		$this->min_time_decline = $min_time_decline;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getMaxPercentDecline()
	{
		return $this->max_percent_decline;
	}

	/**
	 * @param string $max_percent_decline
	 * @return $this
	 */
	public function setMaxPercentDecline($max_percent_decline)
	{
		$this->max_percent_decline = $max_percent_decline;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getIsDailyLimit()
	{
		return $this->is_daily_limit;
	}

	/**
	 * @param int $is_daily_limit
	 * @return $this
	 */
	public function setIsDailyLimit($is_daily_limit)
	{
		$this->is_daily_limit = $is_daily_limit;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getDeadlineNotifyEvent()
	{
		return $this->deadline_notify_event;
	}

	/**
	 * @param int $deadline_notify_event
	 * @return $this
	 */
	public function setDeadlineNotifyEvent($deadline_notify_event)
	{
		$this->deadline_notify_event = $deadline_notify_event;
		return $this;
	}

	/**
	 * @return float
     * @deprecated
	 */
	public function getKrBank()
	{
		return $this->kr_bank;
	}

	/**
	 * @param float $kr_bank
	 * @return $this
     * @deprecated
	 */
	public function setKrBank($kr_bank)
	{
		$this->kr_bank = $kr_bank;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getMinLevel()
	{
		return $this->min_level;
	}

	/**
	 * @param int $min_level
	 * @return $this
	 */
	public function setMinLevel($min_level)
	{
		$this->min_level = $min_level;
		return $this;
	}

    /**
     * @return boolean
     */
    public function isEnableAutoapprove()
    {
        return $this->enable_autoapprove;
    }

    /**
     * @param boolean $enable_autoapprove
     * @return $this
     */
    public function setEnableAutoapprove($enable_autoapprove)
    {
        $this->enable_autoapprove = $enable_autoapprove;
        return $this;
    }

    public function onAfterChange($event)
    {
        $this->raiseEvent('onAfterChange', $event);
    }

    public function updateAction()
    {
        $r = $this->save();

        if($r) {
            if($this->hasEvent('onAfterChange'))
                $this->onAfterChange(new \CEvent($this));
        }

        return $r;
    }
}