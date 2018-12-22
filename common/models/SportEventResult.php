<?php

Yii::import('common.models._base.BaseSportEventResult');

class SportEventResult extends BaseSportEventResult
{
	/**
	 * @param string $className
	 * @return SportEventResult
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
				'updateAttribute' => null,
			]
		];
	}

	public function rules() {
		return [
			['event_id, result_field, value', 'required'],
			['event_id, value, create_at', 'numerical', 'integerOnly'=>true],
			['result_field', 'length', 'max'=>255],
			['create_at', 'default', 'setOnEmpty' => true, 'value' => null],
			['event_id, result_field, value, create_at', 'safe', 'on'=>'search'],
		];
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
	 * @return string
	 */
	public function getResultField()
	{
		return $this->result_field;
	}

	/**
	 * @param string $result_field
	 * @return $this
	 */
	public function setResultField($result_field)
	{
		$this->result_field = $result_field;
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
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * @param int $value
	 * @return $this
	 */
	public function setValue($value)
	{
		$this->value = $value;
		return $this;
	}

	/**
	 * @return SportEvent
	 */
	public function getEvent()
	{
		return $this->event;
	}

	/**
	 * @param SportEvent $event
	 * @return $this
	 */
	public function setEvent($event)
	{
		$this->event = $event;
		return $this;
	}
}