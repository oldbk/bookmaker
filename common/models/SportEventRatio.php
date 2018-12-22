<?php

Yii::import('common.models._base.BaseSportEventRatio');

/**
 * Class SportEventRatio
 *
 * @property int $position
 * @property int $update_at
 */
class SportEventRatio extends BaseSportEventRatio
{
	const POSITION_NEW = 2;
	const POSITION_LAST = 1;
	const POSITION_OLD = 0;

	/**
	 * @param string $className
	 * @return SportEventRatio
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
				'setUpdateOnCreate' => true,
			]
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
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @param string $type
	 * @return $this
	 */
	public function setType($type)
	{
		$this->type = $type;
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
	 * @return string
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * @param string $value
	 * @return $this
	 */
	public function setValue($value)
	{
		$this->value = $value;
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

	/**
	 * @return int
	 */
	public function getPosition()
	{
		return $this->position;
	}

	/**
	 * @param int $position
	 * @return $this
	 */
	public function setPosition($position)
	{
		$this->position = $position;
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

	public static function toOld($event_id)
	{
		SportEventRatio::model()->updateAll([
			'position' => SportEventRatio::POSITION_OLD,
			'update_at' => time()
		], 'event_id = :event_id and position = :position', [
			':event_id' => $event_id,
			':position' => SportEventRatio::POSITION_LAST
		]);
	}

	public static function toLast($event_id)
	{
		SportEventRatio::model()->updateAll([
			'position' => SportEventRatio::POSITION_LAST,
			'update_at' => time()
		], 'event_id = :event_id and position = :position', [
			':event_id' => $event_id,
			':position' => SportEventRatio::POSITION_NEW
		]);
	}

	public static function getByIds($event_ids, $position, $criteriaAdditional = null, $select = ['event_id', 'type', 'value'])
	{
		$return = [];

		$criteria = new \CDbCriteria();
		$criteria->select = $select;
		$criteria->addInCondition('`t`.event_id', $event_ids);
		$criteria->addCondition('`t`.position = :position');
		$criteria->params[':position'] = $position;
		if($criteriaAdditional !== null)
			$criteria->mergeWith($criteriaAdditional);

		$items =  self::model()
			->getCommandBuilder()
			->createFindCommand(\SportEventRatio::model()->tableName(), $criteria)
			->queryAll();
		foreach ($items as $item)
			$return[$item['event_id']][$item['type']] = $item['value'];

		return $return;
	}
}