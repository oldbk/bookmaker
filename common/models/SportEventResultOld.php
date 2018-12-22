<?php

Yii::import('common.models._base.BaseSportEventResultOld');

/**
 * Class SportEventResult
 *
 * @property boolean $is_cancel
 */
class SportEventResultOld extends BaseSportEventResultOld
{
    /**
     * @param string $className
     * @return BaseModel
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
    public function getTeam1Part1()
    {
        return $this->team_1_part_1;
    }

    /**
     * @param int $team_1_part_1
     * @return $this
     */
    public function setTeam1Part1($team_1_part_1)
    {
        $this->team_1_part_1 = $team_1_part_1;
        return $this;
    }

    /**
     * @return int
     */
    public function getTeam2Part1()
    {
        return $this->team_2_part_1;
    }

    /**
     * @param int $team_2_part_1
     * @return $this
     */
    public function setTeam2Part1($team_2_part_1)
    {
        $this->team_2_part_1 = $team_2_part_1;
        return $this;
    }

    /**
     * @return int
     */
    public function getTeam1Part2()
    {
        return $this->team_1_part_2;
    }

    /**
     * @param int $team_1_part_2
     * @return $this
     */
    public function setTeam1Part2($team_1_part_2)
    {
        $this->team_1_part_2 = $team_1_part_2;
        return $this;
    }

    /**
     * @return int
     */
    public function getTeam2Part2()
    {
        return $this->team_2_part_2;
    }

    /**
     * @param int $team_2_part_2
     * @return $this
     */
    public function setTeam2Part2($team_2_part_2)
    {
        $this->team_2_part_2 = $team_2_part_2;
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
     * @return boolean
     */
    public function isCancel()
    {
        return $this->is_cancel;
    }

    /**
     * @param boolean $is_cancel
     * @return $this
     */
    public function setIsCancel($is_cancel)
    {
        $this->is_cancel = $is_cancel;
        return $this;
    }
}