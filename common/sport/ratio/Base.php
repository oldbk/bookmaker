<?php
/**
 * Created by PhpStorm.
 */

namespace common\sport\ratio;

abstract class Base
{
    /** @var null  */
    protected $not_auto_reason = null;
    /** @var boolean */
    protected $is_empty = true;
    /** @var array  */
    protected $original_params = [];
    /** @var int */
    protected $create_at;
    /** @var float */
    protected $itotal_val_1;
    /** @var float */
    protected $itotal_val_2;
    /** @var float */
    protected $itotal_more_1;
    /** @var float */
    protected $itotal_more_2;
    /** @var float */
    protected $itotal_less_1;
    /** @var float */
    protected $itotal_less_2;
    /** @var float */
    protected $fora_val_1;
    /** @var float */
    protected $fora_val_2;
    /** @var float */
    protected $fora_ratio_1;
    /** @var float */
    protected $fora_ratio_2;
    /** @var float */
    protected $total_val;
    /** @var float */
    protected $total_more;
    /** @var float */
    protected $total_less;
    /** @var float */
    protected $ratio_p1;
    /** @var float */
    protected $ratio_p2;
    /** @var float */
    protected $ratio_x2;
    /** @var float */
    protected $ratio_x;

    abstract protected function getRatioList();
    abstract protected function getAutoRequireFields();
    abstract protected function getAttributes();

    public function __construct($attributes = [])
    {
        if(!empty($attributes))
            $this->populateRecord($attributes);
    }

    public function populateRecord($attributes, $prepareRatio = false, $dop_ratio = null, $price_type = null)
    {
        $this->setOriginalParams($attributes);
        if(!empty($attributes))
            $this->setIsEmpty(false);

        if($prepareRatio) {
            foreach ($this->getRatioList() as $field) {
                if(isset($attributes[$field]))
                    $attributes[$field] = \Yii::app()->getSport()->prepareRatio($attributes[$field], $price_type, $dop_ratio);
            }
        }

        foreach ($attributes as $field => $value)
            $this->setAttribute($field, str_replace('–', '-', $value));
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

    /**
     * @param $field
     * @param $value
     */
    public function setAttribute($field, $value)
    {
        if(property_exists($this, $field))
            $this->$field = $value;
    }

    public function getOrigin($field)
    {
        return isset($this->original_params[$field]) ? $this->original_params[$field] : null;
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
     * @return array
     */
    public function getOriginalParams()
    {
        return $this->original_params;
    }

    /**
     * @param array $original_params
     * @return $this
     */
    public function setOriginalParams($original_params)
    {
        $this->original_params = $original_params;
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
     * @return float
     */
    public function getItotalVal1()
    {
        return $this->itotal_val_1;
    }

    /**
     * @param float $itotal_val_1
     * @return $this
     */
    public function setItotalVal1($itotal_val_1)
    {
        $this->itotal_val_1 = $itotal_val_1;
        return $this;
    }

    /**
     * @return float
     */
    public function getItotalVal2()
    {
        return $this->itotal_val_2;
    }

    /**
     * @param float $itotal_val_2
     * @return $this
     */
    public function setItotalVal2($itotal_val_2)
    {
        $this->itotal_val_2 = $itotal_val_2;
        return $this;
    }

    /**
     * @return float
     */
    public function getItotalMore1()
    {
        return $this->itotal_more_1;
    }

    /**
     * @param float $itotal_more_1
     * @return $this
     */
    public function setItotalMore1($itotal_more_1)
    {
        $this->itotal_more_1 = $itotal_more_1;
        return $this;
    }

    /**
     * @return float
     */
    public function getItotalMore2()
    {
        return $this->itotal_more_2;
    }

    /**
     * @param float $itotal_more_2
     * @return $this
     */
    public function setItotalMore2($itotal_more_2)
    {
        $this->itotal_more_2 = $itotal_more_2;
        return $this;
    }

    /**
     * @return float
     */
    public function getItotalLess1()
    {
        return $this->itotal_less_1;
    }

    /**
     * @param float $itotal_less_1
     * @return $this
     */
    public function setItotalLess1($itotal_less_1)
    {
        $this->itotal_less_1 = $itotal_less_1;
        return $this;
    }

    /**
     * @return float
     */
    public function getItotalLess2()
    {
        return $this->itotal_less_2;
    }

    /**
     * @param float $itotal_less_2
     * @return $this
     */
    public function setItotalLess2($itotal_less_2)
    {
        $this->itotal_less_2 = $itotal_less_2;
        return $this;
    }

    /**
     * @return float
     */
    public function getForaVal1()
    {
        return $this->fora_val_1;
    }

    /**
     * @param float $fora_val_1
     * @return $this
     */
    public function setForaVal1($fora_val_1)
    {
        $this->fora_val_1 = $fora_val_1;
        return $this;
    }

    /**
     * @return float
     */
    public function getForaVal2()
    {
        return $this->fora_val_2;
    }

    /**
     * @param float $fora_val_2
     * @return $this
     */
    public function setForaVal2($fora_val_2)
    {
        $this->fora_val_2 = $fora_val_2;
        return $this;
    }

    /**
     * @return float
     */
    public function getForaRatio1()
    {
        return $this->fora_ratio_1;
    }

    /**
     * @param float $fora_ratio_1
     * @return $this
     */
    public function setForaRatio1($fora_ratio_1)
    {
        $this->fora_ratio_1 = $fora_ratio_1;
        return $this;
    }

    /**
     * @return float
     */
    public function getForaRatio2()
    {
        return $this->fora_ratio_2;
    }

    /**
     * @param float $fora_ratio_2
     * @return $this
     */
    public function setForaRatio2($fora_ratio_2)
    {
        $this->fora_ratio_2 = $fora_ratio_2;
        return $this;
    }

    /**
     * @return float
     */
    public function getTotalVal()
    {
        return $this->total_val;
    }

    /**
     * @param float $total_val
     * @return $this
     */
    public function setTotalVal($total_val)
    {
        $this->total_val = $total_val;
        return $this;
    }

    /**
     * @return float
     */
    public function getTotalMore()
    {
        return $this->total_more;
    }

    /**
     * @param float $total_more
     * @return $this
     */
    public function setTotalMore($total_more)
    {
        $this->total_more = $total_more;
        return $this;
    }

    /**
     * @return float
     */
    public function getTotalLess()
    {
        return $this->total_less;
    }

    /**
     * @param float $total_less
     * @return $this
     */
    public function setTotalLess($total_less)
    {
        $this->total_less = $total_less;
        return $this;
    }

    /**
     * @return float
     */
    public function getRatioP1()
    {
        return $this->ratio_p1;
    }

    /**
     * @param float $ratio_p1
     * @return $this
     */
    public function setRatioP1($ratio_p1)
    {
        $this->ratio_p1 = $ratio_p1;
        return $this;
    }

    /**
     * @return float
     */
    public function getRatioP2()
    {
        return $this->ratio_p2;
    }

    /**
     * @param float $ratio_p2
     * @return $this
     */
    public function setRatioP2($ratio_p2)
    {
        $this->ratio_p2 = $ratio_p2;
        return $this;
    }

    /**
     * @return float
     */
    public function getRatioX()
    {
        return $this->ratio_x;
    }

    /**
     * @param float $ratio_x
     * @return $this
     */
    public function setRatioX($ratio_x)
    {
        $this->ratio_x = $ratio_x;
        return $this;
    }

    /**
     * @return null
     */
    public function getNotAutoReason()
    {
        return $this->not_auto_reason;
    }

    /**
     * @param null $not_auto_reason
     *
     * @return $this
     */
    public function setNotAutoReason($not_auto_reason)
    {
        $this->not_auto_reason = $not_auto_reason;
        return $this;
    }

    /**
     * @return bool
     */
    public function canAuto()
    {
        foreach ($this->getAutoRequireFields() as $field => $min_value) {
            $value = $this->getAttribute($field);
            if($value < 0) $value *= (-1);
            if($value === null || (empty($value) && $value != 0)) {
                $message = sprintf('Пустое значение в обязательном поле %s. Значение: %s', $field, $value);
                $this->setNotAutoReason($message);
                return false;
            }

            if($value < $min_value) {
                $message = sprintf('Значение меньше минимального. Поле: %s. Занчение: %s. Минимальное: %s', $field, $value, $min_value);
                $this->setNotAutoReason($message);
                return false;
            }

            if($value > 15 && $min_value != 0) {
                $message = sprintf('Коэф. больше 15. Поле: %s. Значение: %s', $field, $value);
                $this->setNotAutoReason($message);
                return false;
            }
        }

        return true;
    }

    public function insert($event_id, $v, $flash_last = true)
    {
        $t = null;
        if(\Yii::app()->getDb()->getCurrentTransaction() === null)
            $t = \Yii::app()->getDb()->beginTransaction();

        try {
            if($flash_last) {
                \SportEventRatio::toOld($event_id);
                \SportEventRatio::toLast($event_id);
            }

            $builder = \Yii::app()->getDb()->getSchema()->getCommandBuilder();
            $values = [];
            foreach ($this->getAttributes() as $key => $value) {
                if(!is_numeric($value)) continue;

                $values[] = [
                    'event_id' => $event_id,
                    'type' => $key,
                    'value' => $value,
                    '_v' => $v,
                    'position' => \SportEventRatio::POSITION_NEW,
                    'update_at' => time(),
                    'create_at' => time(),
                ];
            }

            $command=$builder->createMultipleInsertCommand(\SportEventRatio::model()->tableName(), $values);
            $command->execute();

            if($t !== null) $t->commit();

            return true;
        } catch (\Exception $ex) {
            if($t !== null) $t->rollback();
        }

        return false;
    }
}