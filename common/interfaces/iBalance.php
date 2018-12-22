<?php
/**
 * Created by PhpStorm.
 */

namespace common\interfaces;


interface iBalance
{
    public function takeBalance($price, $msg = null);
    public function addBalance($price, $msg = null);
}