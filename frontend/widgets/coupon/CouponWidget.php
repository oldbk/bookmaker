<?php
namespace frontend\widgets\coupon;
use CWidget;

class CouponWidget extends CWidget
{
    public function init()
    {
        $link_single = \Yii::app()->createUrl('/bet/single');
        $link_express = \Yii::app()->createUrl('/bet/express');
        $link_prepare = \Yii::app()->createUrl('/bet/info');
        $callback = ['name' => '$coupon.setLinks', 'params' => [$link_express, $link_single, $link_prepare]];

        if(\Yii::app()->getUser()->getName() == 'Байт1') {
            \Yii::app()->getStatic()->setWidget('coupon')
                ->registerCssFile('style.css', !YII_DEBUG)
                ->registerScriptFile('http://s.b.phptd.ru/widgets/buker/coupon/js/script.js', \CClientScript::POS_END, false, $callback);
        } else {
            \Yii::app()->getStatic()->setWidget('coupon')
                ->registerCssFile('style.css', !YII_DEBUG)
                ->registerScriptFile('script.js', \CClientScript::POS_END, !YII_DEBUG, $callback);
        }

        if(!\Yii::app()->getRequest()->getIsAjaxRequest())
            \Yii::app()->getClientScript()->registerScript(uniqid(),
                '$coupon.setLinks("'.$link_express.'", "'.$link_single.'", "'.$link_prepare.'");', \CClientScript::POS_END);
    }

    public function run()
    {
        $this->render('index');
    }
} 