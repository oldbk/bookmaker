<?php
/**
 * Created by PhpStorm.
 */

namespace common\sport\ratio\_interfaces;


interface iRatio
{
    /**
     * @param $attributes
     * @param bool|false $prepareRatio
     * @param float $dop_ratio
     * @param int $price_type
     * @return mixed
     */
    public function populateRecord($attributes, $prepareRatio = false, $dop_ratio = null, $price_type = null);

    public function getOrigin($field);
    public function getAttribute($field);
    public function getAttributes();
    public function getCreateAt();

    /**
     * @param $v
     * @param $event_id
     * @param $flash_last
     * @return boolean
     */
    public function insert($event_id, $v, $flash_last = true);

    /**
     * @return boolean
     */
    public function canAuto();

    /**
     * @return string
     */
    public function getNotAutoReason();
}