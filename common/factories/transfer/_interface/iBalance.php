<?php
/**
 * Created by PhpStorm.
 */

namespace common\factories\transfer\_interface;

/**
 * Interface iTransferBalance
 * @package common\factories\transfer\_interface
 *
 * interface balance operation
 */
interface iBalance
{
    /**
     * @param $price
     * @param null $msg
     * @return bool
     */
    public function take($price, $msg = null);

    /**
     * @param $price
     * @param null $msg
     * @return bool
     */
    public function add($price, $msg = null);
}