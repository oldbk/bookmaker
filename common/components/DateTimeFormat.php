<?php
namespace common\components;

/**
 * Created by PhpStorm.
 * User: Николай
 * Date: 20.11.13
 * Time: 5:38
 */
class DateTimeFormat
{
    public static function format($pattern = null,$time = null)
    {
        if($pattern === null)
            $pattern = \Yii::app()->params['time']['db'];
        if($time === null)
            $time = time();

        return \Yii::app()->dateFormatter->format($pattern, $time);
    }

    public static function getTimeRangeByWord($dateString)
    {
        switch ($dateString) {
            case 'tomorrow':
                return [
                    'start' => strtotime(date("d.m.Y 00:00:00", strtotime('tomorrow'))),
                    'end' => strtotime(date("d.m.Y 23:59:59", strtotime('tomorrow')))
                ];
                break;
            case 'today':
                return [
                    'start' => time(),
                    'end' => strtotime(date('d.m.Y').' 23:59:59')
                ];
                break;
        }

        return false;
    }
} 