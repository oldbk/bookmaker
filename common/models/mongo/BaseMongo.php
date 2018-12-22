<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 30.09.2014
 * Time: 21:20
 */

class BaseMongo extends EMongoDocument
{
    public $_id;

    /**
     * @param string $className
     * @return BaseMongo
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @param $value
     * @param $mongo
     * @return $this
     */
    public function setPrimaryKey($value, $mongo = true)
    {
        if($mongo === true)
            $value = $this->getMongoId($value);

        $_id = $this->getPrimaryKey($value);
        $this->setAttribute($this->primaryKey(), $_id);

        return $this;
    }

    public function getMongoId($value = null)
    {
        try {
            return $value instanceof MongoId ? $value : new MongoId($value);
        } catch (Exception $ex) {
            return null;
        }
    }
} 