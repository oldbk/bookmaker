<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 17.09.2014
 * Time: 13:09
 */

class BaseModel extends GxActiveRecord
{
    /**
     * @param string $className
     * @return BaseModel
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }
} 