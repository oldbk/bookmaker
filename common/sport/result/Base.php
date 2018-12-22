<?php
namespace common\sport\result;
use common\components\NException;

/**
 * Created by PhpStorm.
 */
abstract class Base
{
    /** @var boolean */
    protected $is_empty = true;
    /** @var int */
    protected $team_2_result;
    /** @var int */
    protected $team_1_result;
    /** @var bool */
    protected $is_cancel = false;
    /** @var int */
    protected $event_id;
    /** @var int */
    protected $create_at;

    abstract protected function getDuringResultByTeam();
    abstract protected function getDuringResult();

    public function __construct($attributes = [])
    {
        if(!empty($attributes))
            $this->populateRecord($attributes);
    }

    public function getAttributes()
    {
        return \CMap::mergeArray([
            'team_1_result' => $this->getTeam1Result(),
            'team_2_result' => $this->getTeam2Result(),
            'is_cancel' => $this->isCancel(),
        ],  $this->getDuringResult());
    }

    /**
     * @return mixed
     */
    public function getEventId()
    {
        return $this->event_id;
    }

    /**
     * @param mixed $event_id
     * @return $this
     */
    public function setEventId($event_id)
    {
        $this->event_id = $event_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreateAt()
    {
        return $this->create_at;
    }

    /**
     * @param mixed $create_at
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
    public function getTeam1Result()
    {
        return $this->team_1_result;
    }

    /**
     * @param int $team_1_result
     * @return $this
     */
    public function setTeam1Result($team_1_result)
    {
        $this->team_1_result = $team_1_result;
        return $this;
    }
    /**
     * @return int
     */
    public function getTeam2Result()
    {
        return $this->team_2_result;
    }

    /**
     * @param int $team_2_result
     * @return $this
     */
    public function setTeam2Result($team_2_result)
    {
        $this->team_2_result = $team_2_result;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isEmpty()
    {
        return $this->is_empty;
    }

    /**
     * @param boolean $is_empty
     * @return $this
     */
    public function setIsEmpty($is_empty)
    {
        $this->is_empty = $is_empty;
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

    public function populateRecord($attributes)
    {
        if(!empty($attributes))
            $this->setIsEmpty(false);

        foreach ($attributes as $field => $value)
            $this->setAttribute($field, $value);
    }

    /**
     * @param $field
     * @param $value
     * @return $this
     */
    public function setAttribute($field, $value)
    {
        if(property_exists($this, $field))
            $this->$field = $value;

        return $this;
    }

    /**
     * @param $field
     * @return mixed
     */
    public function getAttribute($field)
    {
        if(property_exists($this, $field))
            return $this->$field;

        return null;
    }

    public function getResultString()
    {
        if($this->isCancel())
            return 'матч отменен/не состоялся';

        if($this->getTeam1Result() === null || $this->getTeam2Result() === null)
            return null;

        $string = sprintf('%s:%s', $this->getTeam1Result(), $this->getTeam2Result()).' ';
        foreach ($this->getDuringResultByTeam() as $teams) {
            if($teams[0] === null || $teams[1] === null) continue;
            $string .= sprintf('(%s:%s), ', $teams[0], $teams[1]);
        }
        $string = trim($string, ', ');

        return $string;
    }

    public function getDuringSumTeam1()
    {
        $sum = 0;
        foreach ($this->getDuringResultByTeam() as $teams) {
            if(empty($teams[0]) && $teams[0] != 0) continue;
            $sum += $teams[0];
        }

        return $sum;
    }

    public function getDuringSumTeam2()
    {
        $sum = 0;
        foreach ($this->getDuringResultByTeam() as $teams) {
            if(empty($teams[1]) && $teams[1] != 0) continue;
            $sum += $teams[1];
        }

        return $sum;
    }

    public function insert($event_id)
    {
        $t = null;
        if(\Yii::app()->getDb()->getCurrentTransaction() === null)
            $t = \Yii::app()->getDb()->beginTransaction();

        try {
            $builder = \Yii::app()->getDb()->getSchema()->getCommandBuilder();
            $values = [];

            $criteria = new \CDbCriteria();
            $criteria->addCondition('event_id = :event_id');
            $criteria->params = [':event_id' => $event_id];
            \SportEventResult::model()->deleteAll($criteria);

            foreach ($this->getAttributes() as $key => $value) {
                if(!is_numeric($value) && !is_bool($value)) continue;

                $values[] = [
                    'event_id' => $event_id,
                    'result_field' => $key,
                    'value' => $value,
                    'create_at' => time(),
                ];
            }

            $command=$builder->createMultipleInsertCommand(\SportEventResult::model()->tableName(), $values);
            $command->execute();

            if($t !== null) $t->commit();

            return true;
        } catch (\Exception $ex) {
            if($t !== null) $t->rollback();
            \MException::logMongo($ex);
        }

        return false;
    }

    public function doEmpty()
    {
        foreach ($this->getAttributes() as $key => $value)
            $this->setAttribute($key, 0);

        return $this;
    }
}