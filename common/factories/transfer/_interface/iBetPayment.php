<?php
/**
 * Created by PhpStorm.
 */

namespace common\factories\transfer\_interface;


interface iBetPayment
{
    public function run($price, $msg = null);
}