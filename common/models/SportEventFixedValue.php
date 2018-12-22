<?php

Yii::import('common.models._base.BaseSportEventFixedValue');

class SportEventFixedValue extends BaseSportEventFixedValue
{
    /**
     * @param string $className
     * @return SportEventFixedValue
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
                'updateAttribute' => null
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
    public function getRatioName()
    {
        return $this->ratio_name;
    }

    /**
     * @param string $ratio_name
     * @return $this
     */
    public function setRatioName($ratio_name)
    {
        $this->ratio_name = $ratio_name;
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