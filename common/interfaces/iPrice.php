<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 11.02.2015
 * Time: 20:22
 */

namespace common\interfaces;


interface iPrice
{
    const TYPE_KR       = 0;
    const TYPE_EKR      = 1;
    const TYPE_VOUCHER  = 2;
    const TYPE_GOLD  	= 3;

    const OPERATION_TYPE_ADD    = 0;
    const OPERATION_TYPE_TAKE   = 1;
    const OPERATION_TYPE_RETURN = 2;
    const OPERATION_TYPE_AKCII  = 3;
}