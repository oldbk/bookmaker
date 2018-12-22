<?php
namespace common\factories\data_to_view\_base;

use common\helpers\Convert;

;

/**
 * Created by PhpStorm.
 */

abstract class BaseDataToView
{
    /** @var \SportEvent */
    protected $event;

    private $_result_label = [];

    abstract public function getMappingLabel();

    /**
     * @param \SportEvent $Event
     */
    public function __construct(&$Event)
    {
        $this->setEvent($Event);

        foreach ($this->getMappingLabel() as $field => $label)
            $this->setResultLabel($field, $this->prepareLabel($label));
    }

    /**
     * @return \SportEvent
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param \SportEvent $event
     * @return $this
     */
    public function setEvent(&$event)
    {
        $this->event = $event;
        return $this;
    }

    public function getByName($field_name)
    {
        $methodName = 'get';
        $_temp = explode('_', $field_name);
        foreach ($_temp as $name)
            $methodName .= ucfirst($name);
        if(method_exists($this, $methodName))
            return call_user_func_array([$this, $methodName], []);
        else
            return null;
    }

    public function buildMessageResultString($alias)
    {
        $field = $this->getEvent()->getFieldByAlias($alias);
        return $this->getResultLabel($field);
    }

    /**
     * @param $label
     * @return mixed
     */
    protected function prepareLabel($label)
    {
        $replaced = [];
        foreach ($this->getEvent()->getAttributes() as $name => $value) {
            if(($vv = $this->getByName($name)) !== null)
                $value = $vv;
            $replaced['%' . $name . '%'] = $value;
        }

        foreach ($this->getEvent()->getNewRatio()->getAttributes() as $name => $value) {
            if(($vv = $this->getByName($name)) !== null)
                $value = $vv;
            $replaced['%' . $name . '%'] = $value;
        }

        return str_replace(array_keys($replaced), array_values($replaced), $label);
    }

    public function getTitle()
    {
        return $this->getEvent()->getTitle();
    }

    public function getForaVal1()
    {
        $value = $this->getEvent()->getNewRatio()->getForaVal1();
        if($value === null)
            return null;
        if($value == 0)
            return 0;

        $show = '';
        if($value > 0)
            $show .= '+';
        $value = str_replace(',', '.', $value);
        if(strval($value * 100) % 10 == 0)
            $show .= Convert::getFormat($value, 1);
        else
            $show .= Convert::getFormat($value, 2);

        return $show;
    }

    public function getForaVal2()
    {
        $value = $this->getEvent()->getNewRatio()->getForaVal2();
        if($value === null)
            return null;
        if($value == 0)
            return 0;

        $show = '';
        if($value > 0)
            $show .= '+';
        $value = str_replace(',', '.', $value);
        if(strval($value * 100) % 10 == 0)
            $show .= Convert::getFormat($value, 1);
        else
            $show .= Convert::getFormat($value, 2);

        return $show;
    }

    public function getForaRatio1()
    {
        return $this->getEvent()->getNewRatio()->getForaRatio1();
    }

    public function getForaRatio2()
    {
        return $this->getEvent()->getNewRatio()->getForaRatio2();
    }

    public function getTotalVal()
    {
        $value = $this->getEvent()->getNewRatio()->getTotalVal();
        if($value === null)
            return null;
        if($value == 0)
            return 0;

        $show = '';
        $value = str_replace(',', '.', $value);
        if(strval($value * 100) % 10 == 0)
            $show .= Convert::getFormat($value, 1);
        else
            $show .= Convert::getFormat($value, 2);

        return $show;
    }

    public function getTotalMore()
    {
        return $this->getEvent()->getNewRatio()->getTotalMore();
    }

    public function getTotalLess()
    {
        return $this->getEvent()->getNewRatio()->getTotalLess();
    }

    public function getRatioP1()
    {
        return $this->getEvent()->getNewRatio()->getRatioP1();
    }

    public function getRatioX()
    {
        return $this->getEvent()->getNewRatio()->getRatioX();
    }

    public function getRatioP2()
    {
        return $this->getEvent()->getNewRatio()->getRatioP2();
    }

    public function getRatio1x()
    {
        return $this->getEvent()->getNewRatio()->getRatio1x();
    }

    public function getRatio12()
    {
        return $this->getEvent()->getNewRatio()->getRatio12();
    }

    public function getRatioX2()
    {
        return $this->getEvent()->getNewRatio()->getRatioX2();
    }

    public function getITotalVal1()
    {
        $value = $this->getEvent()->getNewRatio()->getITotalVal1();
        if($value === null)
            return null;
        if($value == 0)
            return 0;

        $show = '';
        $value = str_replace(',', '.', $value);
        if(strval($value * 100) % 10 == 0)
            $show .= Convert::getFormat($value, 1);
        else
            $show .= Convert::getFormat($value, 2);

        return $show;
    }

    public function getITotalVal2()
    {
        $value = $this->getEvent()->getNewRatio()->getITotalVal2();
        if($value === null)
            return null;
        if($value == 0)
            return 0;

        $show = '';
        $value = str_replace(',', '.', $value);
        if(strval($value * 100) % 10 == 0)
            $show .= Convert::getFormat($value, 1);
        else
            $show .= Convert::getFormat($value, 2);

        return $show;
    }

    public function getITotalMore1()
    {
        return $this->getEvent()->getNewRatio()->getItotalMore1();
    }

    public function getITotalMore2()
    {
        return $this->getEvent()->getNewRatio()->getItotalMore2();
    }

    public function getITotalLess1()
    {
        return $this->getEvent()->getNewRatio()->getItotalLess1();
    }

    public function getITotalLess2()
    {
        return $this->getEvent()->getNewRatio()->getItotalLess2();
    }

    /**
     * @param null $field
     * @return array|null
     */
    public function getResultLabel($field = null)
    {
        if($field === null) return $this->_result_label;

        return isset($this->_result_label[$field]) ? $this->_result_label[$field] : null;
    }

    /**
     * @param $field
     * @param $value
     * @return $this
     */
    public function setResultLabel($field, $value)
    {
        $this->_result_label[$field] = $value;
        return $this;
    }
}