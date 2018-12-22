<?php
/**
 * Created by PhpStorm.
 */

namespace common\factories;

class BaseFactory
{
    protected static function prepareClassName($name)
    {
        $methodName = '';
        $_temp = explode('_', $name);
        foreach ($_temp as $name)
            $methodName .= ucfirst($name);

        return trim($methodName);
    }
}