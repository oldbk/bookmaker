<?php
namespace common\helpers;
/**
 * Created by PhpStorm.
 * User: me
 * Date: 10.12.2014
 * Time: 2:36
 */

use Yii;
class SiteHelper
{
    public static function getBG()
    {
        if(date('H') > 6 && date('H') < 20)
            return Yii::app()->getStatic()->imageLink('www/'.Yii::app()->getTheme()->name.'/images/bg.jpg');
        else
            return Yii::app()->getStatic()->imageLink('www/'.Yii::app()->getTheme()->name.'/images/bg.jpg');
    }
} 