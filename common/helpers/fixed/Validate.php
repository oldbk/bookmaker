<?php
namespace common\helpers\fixed;
/**
 * Created by PhpStorm.
 *
 * @method \SportEvent date($value)
 */
class Validate
{
    protected function defaultValidate($value)
    {
        return true;
    }

    protected function dateValidate($value)
    {
        if(!preg_match('/(\d{2})\/(\d{2}) (\d{2}\:\d{2})/ui', $value))
            return 'Неверный формат. дд/мм чч:мм';

        return true;
    }

    public function __call($name, $args)
    {
        $name = $name.'Validate';

        if( method_exists($this, $name))
            return call_user_func_array([$this, $name], $args);
        else
            return call_user_func_array([$this, 'defaultValidate'], $args);
    }
}