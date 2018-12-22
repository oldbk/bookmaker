<?php
namespace common\components;
/**
 * Created by PhpStorm.
 */

class DateFormat
{
    public static function getDate($format, $time)
    {
        return strftime($format, $time);
    }

    public static function getWithAMPM($format, $time)
    {
        $postfix = date('A', $time);

        return str_replace('PMAM', $postfix, strftime($format, $time));
    }
}