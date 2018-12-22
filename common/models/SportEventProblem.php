<?php

Yii::import('common.models._base.BaseSportEventProblem');

/**
 * Class SportEventProblem
 *
 * Relations
 * @property SportEvent $event
 *
 * @property string $custom
 */
class SportEventProblem extends BaseSportEventProblem
{
    const PROBLEM_DATE = 'date';
    const PROBLEM_FORA = 'fora';
    const PROBLEM_NO_RESULT = 'no_result';
    const PROBLEM_FORA_WIN = 'fora_win';
    const PROBLEM_SPORT_ID = 'sport_id';

    /**
     * @param string $className
     * @return SportEventProblem
     */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

    public function scopes()
    {
        $t = $this->getTableAlias(false, false);
        return [
            'notResolve' => [
                'condition' => "`{$t}`.is_resolved = 0",
            ],
            'resolve' => [
                'condition' => "`{$t}`.is_resolved = 1",
            ],
        ];
    }

    public function relations() {
        return [
            'event' => [
                self::BELONGS_TO,
                'SportEvent',
                'event_id',
                'joinType' => 'inner join'
            ],
        ];
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
     * @return string
     */
    public function getProblemType()
    {
        return $this->problem_type;
    }

    /**
     * @param string $problem_type
     * @return $this
     */
    public function setProblemType($problem_type)
    {
        $this->problem_type = $problem_type;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return int
     */
    public function getIsResolved()
    {
        return $this->is_resolved;
    }

    /**
     * @param int $is_resolved
     * @return $this
     */
    public function setIsResolved($is_resolved)
    {
        $this->is_resolved = $is_resolved;
        return $this;
    }

    /**
     * @return int
     */
    public function getResolverId()
    {
        return $this->resolver_id;
    }

    /**
     * @param int $resolver_id
     * @return $this
     */
    public function setResolverId($resolver_id)
    {
        $this->resolver_id = $resolver_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getAutoResolved()
    {
        return $this->auto_resolved;
    }

    /**
     * @param int $auto_resolved
     * @return $this
     */
    public function setAutoResolved($auto_resolved)
    {
        $this->auto_resolved = $auto_resolved;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatusBefore()
    {
        return $this->status_before;
    }

    /**
     * @param int $status_before
     * @return $this
     */
    public function setStatusBefore($status_before)
    {
        $this->status_before = $status_before;
        return $this;
    }

    /**
     * @return int
     */
    public function getIsFreeze()
    {
        return $this->is_freeze;
    }

    /**
     * @param int $is_freeze
     * @return $this
     */
    public function setIsFreeze($is_freeze)
    {
        $this->is_freeze = $is_freeze;
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
    public function getProblemValue()
    {
        return $this->problem_value;
    }

    /**
     * @param string $problem_value
     * @return $this
     */
    public function setProblemValue($problem_value)
    {
        $this->problem_value = $problem_value;
        return $this;
    }

    /**
     * @return string
     */
    public function getCustom()
    {
        return $this->custom;
    }

    /**
     * @param string $custom
     * @return $this
     */
    public function setCustom($custom)
    {
        $this->custom = $custom;
        return $this;
    }

    public static function getProblemList()
    {
        return array_keys(self::$_problem_label);
    }

    private static $_problem_label = [
        self::PROBLEM_DATE => 'Дата',
        self::PROBLEM_FORA => 'Фора',
        self::PROBLEM_NO_RESULT => 'Нет результата',
        self::PROBLEM_FORA_WIN => 'Форы и П1\П2',
        self::PROBLEM_SPORT_ID => 'Лига',
    ];

    public function getProblemTypeLabel()
    {
        return self::$_problem_label[$this->getProblemType()];
    }
}