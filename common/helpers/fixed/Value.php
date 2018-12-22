<?php
namespace common\helpers\fixed;

/**
 * Class Ratio
 * @package common\helpers\fixed
 *
 * @method \SportEvent date($value)
 * @property int date_int
 * @property string date_string
 */
class Value
{
    private $_fixedRatio = [];

    /**
     * @param $name
     * @param $value
     * @return array
     */
    public function dateField($name, $value)
    {
        preg_match('/(\d{2})\/(\d{2}) (\d{2}\:\d{2})/ui', $value, $out);

        $this->_fixedRatio['date_string'] = sprintf('%s.%s.%s %s:00', $out[1], $out[2], date('Y'), $out[3]);
        $this->_fixedRatio['date_int'] = strtotime(sprintf('%s-%s-%s %s:00', date('Y'), $out[2], $out[1], $out[3]));

        return $this->_fixedRatio;
    }

    /**
     * @param $name
     * @param $value
     * @return array
     */
    public function defaultField($name, $value)
    {
        $this->_fixedRatio[$name] = $value;

        return $this->_fixedRatio;
    }

    public function getFixed()
    {
        return $this->_fixedRatio;
    }

    public function __call($name, $args)
    {
        $name = $name.'Field';

        if( method_exists($this, $name))
            return call_user_func_array([$this, $name], $args);
        else
            return call_user_func_array([$this, 'defaultField'], $args);
    }

    public function __get($name)
    {
        if(isset($this->_fixedRatio[$name]))
            return $this->_fixedRatio[$name];
        else
            return null;
    }
}