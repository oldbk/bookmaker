<?php
/**
 * Created by PhpStorm.
 */

namespace common\factories\transfer\_interface;


interface iModer
{
    /**
     * @param $price
     * @return boolean
     */
    public function run($price);

    /**
     * @param $balance_id
     * @return boolean
     */
    public function accept($balance_id);

    /**
     * @param $balance_id
     * @return boolean
     */
    public function cancel($balance_id);

    /**
     * @param $balance_id
     * @return boolean
     */
    public function decline($balance_id);
}