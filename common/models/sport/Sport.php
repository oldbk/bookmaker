<?php

Yii::import('common.models._base.BaseSport');
use \common\interfaces\iStatus;
/**
 * Class Sport
 *
 * @property int event_active_count
 * @property int newEventCount
 * @property int allEventCount
 * @property int enableEventCount
 * @property int sport_type
 * @property string sport_template
 *
 * @property SportEvent[] sportEventsFinish
 */
class Sport extends BaseSport
{
	public $cnt;
	/**
	 * @param string $className
	 * @return Sport
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
			'sportEvents' => [
				self::HAS_MANY,
				'SportEvent',
				'sport_id',
				'joinType' => 'inner join',
			],
			'sportEventsFinish' => [
				self::HAS_MANY,
				'SportEvent',
				'sport_id',
				'joinType' => 'inner join',
				'on' => 'sportEventsFinish.have_result = 1'
			],
			'sportEventPreviouses' => [self::HAS_MANY, 'SportEventPrevious', 'sport_id'],
			'allEventCount' => [
				self::STAT,
				'sportEvent',
				'sport_id',
                'condition' => 't.status != :allEventCount_finish and t.is_trash = 0',
                'params' => [':allEventCount_finish' => iStatus::STATUS_FINISH]
			],
			'newEventCount' => [
				self::STAT,
				'SportEvent',
				'sport_id',
                'condition' => 't.date_int > :enableEventCount_date_int and t.have_problem = 0 and t.status = :enableEventCount_new and t.is_trash = 0',
				'params' => [':enableEventCount_date_int' => time(), ':enableEventCount_new' => iStatus::STATUS_NEW],
			],
            'enableEventCount' => [
                self::STAT,
                'SportEvent',
                'sport_id',
                'condition' => 't.date_int > :enableEventCount_date_int and t.have_problem = 0 and t.status = :enable and t.is_trash = 0',
                'params' => [':enableEventCount_date_int' => time(), ':enable' => iStatus::STATUS_ENABLE],
            ],
		];
	}

	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @param string $title
	 * @return $this
	 */
	public function setTitle($title)
	{
		$this->title = $title;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getLink()
	{
		return $this->link;
	}

	/**
	 * @param string $link
	 * @return $this
	 */
	public function setLink($link)
	{
		$this->link = $link;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getEventCount()
	{
		return $this->event_count;
	}

	/**
	 * @param int $event_count
	 * @return $this
	 */
	public function setEventCount($event_count)
	{
		$this->event_count = $event_count;
		return $this;
	}

	/**
	 * @param string $format
	 * @return int
	 */
	public function getUpdateAt($format = null)
	{
		if($format === null) return $this->update_at;

		return date($format, $this->update_at);
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
	public function getIsBlocked()
	{
		return $this->is_blocked;
	}

	/**
	 * @param int $is_blocked
	 * @return $this
	 */
	public function setIsBlocked($is_blocked)
	{
		$this->is_blocked = $is_blocked;
		return $this;
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
	public function getEventActiveCount()
	{
		return $this->event_active_count;
	}

	/**
	 * @param int $event_active_count
	 * @return $this
	 */
	public function setEventActiveCount($event_active_count)
	{
		$this->event_active_count = $event_active_count;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getSportTemplate()
	{
		return $this->sport_template;
	}

	/**
	 * @param string $sport_template
	 * @return $this
	 */
	public function setSportTemplate($sport_template)
	{
		$this->sport_template = $sport_template;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getSportType()
	{
		return $this->sport_type;
	}

	/**
	 * @param int $sport_type
	 * @return $this
	 */
	public function setSportType($sport_type)
	{
		$this->sport_type = $sport_type;
		return $this;
	}

	public function getSportTypeView()
	{
		return \common\helpers\SportHelper::getByID($this->getSportType());
	}
}