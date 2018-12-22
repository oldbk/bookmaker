<?php
/**
 * Created by PhpStorm.
 */

namespace common\interfaces;


interface iAdminLog
{
    public function getOldAttributes();
    public function getNewAttributes();
    public function getCompareList();
    public function getId();
}