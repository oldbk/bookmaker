<?php
/**
 * Created by PhpStorm.
 */

namespace common\factories\transfer\_interface;

/**
 * Interface iTransferIO
 * @package common\factories\transfer\_interface
 *
 * interface input\output
 */
interface iIO
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