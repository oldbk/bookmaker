<?php
/**
 * Created by PhpStorm.
 */

namespace common\components\oldbk;


class Base
{
    public function __construct($attributes = [])
    {
        foreach ($attributes as $name => $value) {
            if(property_exists($this, $name))
                $this->{$name} = $value;
        }
    }
}