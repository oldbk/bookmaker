<?php
namespace common\components;
/**
 * Created by PhpStorm.
 * User: me
 * Date: 02.11.2014
 * Time: 1:23
 */
use Yii;
use CException;

Yii::import('common.extensions.YiiMailer.YiiMailer');
class Mailer extends \YiiMailer
{
    public function __construct($view='', $data=array(), $layout='')
    {
        //initialize config
        if(isset(Yii::app()->params[self::CONFIG_PARAMS]))
            $config=Yii::app()->params[self::CONFIG_PARAMS];
        else
            $config=require(Yii::getPathOfAlias('common.config').DIRECTORY_SEPARATOR.self::CONFIG_FILE);
        //set config
        $this->setConfig($config);
        //set view
        $this->setView($view);
        //set data
        $this->setData($data);
        //set layout
        $this->setLayout($layout);
    }

    private function setConfig($config)
    {
        if(!is_array($config))
            throw new CException("Configuration options must be an array!");
        foreach($config as $key=>$val)
        {
            $this->$key=$val;
        }
    }
} 