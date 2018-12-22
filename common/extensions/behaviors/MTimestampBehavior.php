<?php
namespace common\extensions\behaviors;
/**
 * Created by PhpStorm.
 * User: me
 * Date: 14.02.2015
 * Time: 3:04
 */

use Yii;
use CTimestampBehavior;

Yii::import('zii.behaviors.CTimestampBehavior');

/**
 * Class MTimestampBehavior
 * @package common\extensions\behaviors
 *
 * @method \CActiveRecord getOwner()
 */
class MTimestampBehavior extends CTimestampBehavior
{
    public $updateDatetimeAttribute = null;
    public $createDatetimeAttribute = null;

    public function beforeSave($event) {
        if ($this->getOwner()->getIsNewRecord() && ($this->createAttribute !== null)) {
            $this->getOwner()->{$this->createAttribute} = $this->getTimestampByAttribute($this->createAttribute);

            if($this->createDatetimeAttribute !== null)
                $this->getOwner()->{$this->createDatetimeAttribute} = date('Y-m-d H:i:s', $this->getOwner()->{$this->createAttribute});
        }
        if ((!$this->getOwner()->getIsNewRecord() || $this->setUpdateOnCreate) && ($this->updateAttribute !== null)) {
            $this->getOwner()->{$this->updateAttribute} = $this->getTimestampByAttribute($this->updateAttribute);
        }
    }
}