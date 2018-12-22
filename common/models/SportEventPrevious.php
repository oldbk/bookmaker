<?php

Yii::import('common.models._base.BaseSportEventPrevious');

/**
 * Class SportEventPrevious
 *
 * Relations
 * @property SportEventPrevious previousLast
 *
 * @property int _v
 * @property int team_1_id
 * @property int team_2_id
 */
class SportEventPrevious extends BaseSportEventPrevious implements \common\interfaces\iStatus
{
    use \common\traits\tEvent;

    /**
     * @param string $className
     * @return SportEventPrevious
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
            'sport' => [self::BELONGS_TO, 'Sport', 'sport_id', 'joinType' => 'inner join'],
            'previousLast' => [
                self::HAS_ONE,
                'SportEventPrevious',
                ['sport_event_id' => 'sport_event_id'],
                'joinType' => 'left join',
                'order' => 'previousLast._v asc',
                'condition' => 'previousLast._v < t._v or previousLast._v = t._v',
            ],
        ];
    }

    /**
     * @return int
     */
    public function getSportEventId()
    {
        return $this->sport_event_id;
    }

    /**
     * @param int $sport_event_id
     * @return $this
     */
    public function setSportEventId($sport_event_id)
    {
        $this->sport_event_id = $sport_event_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getSportId()
    {
        return $this->sport_id;
    }

    /**
     * @param int $sport_id
     * @return $this
     */
    public function setSportId($sport_id)
    {
        $this->sport_id = $sport_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getSportTitle()
    {
        return $this->sport_title;
    }

    /**
     * @param string $sport_title
     * @return $this
     */
    public function setSportTitle($sport_title)
    {
        $this->sport_title = $sport_title;
        return $this;
    }

    /**
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param string $number
     * @return $this
     */
    public function setNumber($number)
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @param string|null $format
     * @return int
     */
    public function getDateInt($format = null)
    {
        if($format === null) return $this->date_int;

        return date($format, $this->date_int);
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
     * @return string
     */
    public function getDateString()
    {
        return $this->date_string;
    }

    /**
     * @param string $date_string
     * @return $this
     */
    public function setDateString($date_string)
    {
        $this->date_string = $date_string;
        return $this;
    }

    /**
     * @return string
     */
    public function getTeam1()
    {
        return $this->team_1;
    }

    /**
     * @param string $team_1
     * @return $this
     */
    public function setTeam1($team_1)
    {
        $this->team_1 = $team_1;
        return $this;
    }

    /**
     * @return string
     */
    public function getTeam2()
    {
        return $this->team_2;
    }

    /**
     * @param string $team_2
     * @return $this
     */
    public function setTeam2($team_2)
    {
        $this->team_2 = $team_2;
        return $this;
    }

    /**
     * @return string
     */
    public function getForaVal1()
    {
        return $this->fora_val_1;
    }

    /**
     * @param string $fora_val_1
     * @return $this
     */
    public function setForaVal1($fora_val_1)
    {
        $this->fora_val_1 = $fora_val_1;
        return $this;
    }

    /**
     * @return string
     */
    public function getForaVal2()
    {
        return $this->fora_val_2;
    }

    /**
     * @param string $fora_val_2
     * @return $this
     */
    public function setForaVal2($fora_val_2)
    {
        $this->fora_val_2 = $fora_val_2;
        return $this;
    }

    /**
     * @return string
     */
    public function getForaRatio1()
    {
        return $this->fora_ratio_1;
    }

    /**
     * @param string $fora_ratio_1
     * @return $this
     */
    public function setForaRatio1($fora_ratio_1)
    {
        $this->fora_ratio_1 = $fora_ratio_1;
        return $this;
    }

    /**
     * @return string
     */
    public function getForaRatio2()
    {
        return $this->fora_ratio_2;
    }

    /**
     * @param string $fora_ratio_2
     * @return $this
     */
    public function setForaRatio2($fora_ratio_2)
    {
        $this->fora_ratio_2 = $fora_ratio_2;
        return $this;
    }

    /**
     * @return string
     */
    public function getTotalVal()
    {
        return $this->total_val;
    }

    /**
     * @param string $total_val
     * @return $this
     */
    public function setTotalVal($total_val)
    {
        $this->total_val = $total_val;
        return $this;
    }

    /**
     * @return string
     */
    public function getTotalMore()
    {
        return $this->total_more;
    }

    /**
     * @param string $total_more
     * @return $this
     */
    public function setTotalMore($total_more)
    {
        $this->total_more = $total_more;
        return $this;
    }

    /**
     * @return string
     */
    public function getTotalLess()
    {
        return $this->total_less;
    }

    /**
     * @param string $total_less
     * @return $this
     */
    public function setTotalLess($total_less)
    {
        $this->total_less = $total_less;
        return $this;
    }

    /**
     * @return string
     */
    public function getRatioP1()
    {
        return $this->ratio_p1;
    }

    /**
     * @param string $ratio_p1
     * @return $this
     */
    public function setRatioP1($ratio_p1)
    {
        $this->ratio_p1 = $ratio_p1;
        return $this;
    }

    /**
     * @return string
     */
    public function getRatioX()
    {
        return $this->ratio_x;
    }

    /**
     * @param string $ratio_x
     * @return $this
     */
    public function setRatioX($ratio_x)
    {
        $this->ratio_x = $ratio_x;
        return $this;
    }

    /**
     * @return string
     */
    public function getRatioP2()
    {
        return $this->ratio_p2;
    }

    /**
     * @param string $ratio_p2
     * @return $this
     */
    public function setRatioP2($ratio_p2)
    {
        $this->ratio_p2 = $ratio_p2;
        return $this;
    }

    /**
     * @return string
     */
    public function getRatio1x()
    {
        return $this->ratio_1x;
    }

    /**
     * @param string $ratio_1x
     * @return $this
     */
    public function setRatio1x($ratio_1x)
    {
        $this->ratio_1x = $ratio_1x;
        return $this;
    }

    /**
     * @return string
     */
    public function getRatio12()
    {
        return $this->ratio_12;
    }

    /**
     * @param string $ratio_12
     * @return $this
     */
    public function setRatio12($ratio_12)
    {
        $this->ratio_12 = $ratio_12;
        return $this;
    }

    /**
     * @return string
     */
    public function getRatioX2()
    {
        return $this->ratio_x2;
    }

    /**
     * @param string $ratio_x2
     * @return $this
     */
    public function setRatioX2($ratio_x2)
    {
        $this->ratio_x2 = $ratio_x2;
        return $this;
    }

    /**
     * @return string
     */
    public function getItotalVal1()
    {
        return $this->itotal_val_1;
    }

    /**
     * @param string $itotal_val_1
     * @return $this
     */
    public function setItotalVal1($itotal_val_1)
    {
        $this->itotal_val_1 = $itotal_val_1;
        return $this;
    }

    /**
     * @return string
     */
    public function getItotalVal2()
    {
        return $this->itotal_val_2;
    }

    /**
     * @param string $itotal_val_2
     * @return $this
     */
    public function setItotalVal2($itotal_val_2)
    {
        $this->itotal_val_2 = $itotal_val_2;
        return $this;
    }

    /**
     * @return string
     */
    public function getItotalMore1()
    {
        return $this->itotal_more_1;
    }

    /**
     * @param string $itotal_more_1
     * @return $this
     */
    public function setItotalMore1($itotal_more_1)
    {
        $this->itotal_more_1 = $itotal_more_1;
        return $this;
    }

    /**
     * @return string
     */
    public function getItotalMore2()
    {
        return $this->itotal_more_2;
    }

    /**
     * @param string $itotal_more_2
     * @return $this
     */
    public function setItotalMore2($itotal_more_2)
    {
        $this->itotal_more_2 = $itotal_more_2;
        return $this;
    }

    /**
     * @return string
     */
    public function getItotalLess1()
    {
        return $this->itotal_less_1;
    }

    /**
     * @param string $itotal_less_1
     * @return $this
     */
    public function setItotalLess1($itotal_less_1)
    {
        $this->itotal_less_1 = $itotal_less_1;
        return $this;
    }

    /**
     * @return string
     */
    public function getItotalLess2()
    {
        return $this->itotal_less_2;
    }

    /**
     * @param string $itotal_less_2
     * @return $this
     */
    public function setItotalLess2($itotal_less_2)
    {
        $this->itotal_less_2 = $itotal_less_2;
        return $this;
    }

    /**
     * @return int
     */
    public function getEventType()
    {
        return $this->event_type;
    }

    /**
     * @param int $event_type
     * @return $this
     */
    public function setEventType($event_type)
    {
        $this->event_type = $event_type;
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
    public function getTeam1Id()
    {
        return $this->team_1_id;
    }

    /**
     * @param int $team_1_id
     * @return $this
     */
    public function setTeam1Id($team_1_id)
    {
        $this->team_1_id = $team_1_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getTeam2Id()
    {
        return $this->team_2_id;
    }

    /**
     * @param int $team_2_id
     * @return $this
     */
    public function setTeam2Id($team_2_id)
    {
        $this->team_2_id = $team_2_id;
        return $this;
    }
}