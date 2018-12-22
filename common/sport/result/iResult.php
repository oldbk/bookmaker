<?php
/**
 * Created by PhpStorm.
 */

namespace common\sport\result;


interface iResult
{
    /**
     * @return array
     */
    public function getAttributes();
    /**
     * @return int
     */
    public function getTeam1Result();

    /**
     * @return int
     */
    public function getTeam2Result();

    /**
     * @param $event_id
     * @return boolean
     */
    public function insert($event_id);

    /**
     * @return boolean
     */
    public function isEmpty();

    /**
     * @return boolean
     */
    public function isCancel();

    /**
     * @return string
     */
    public function getResultString();

    /**
     * @param $attributes
     * @return $this
     */
    public function populateRecord($attributes);

    /**
     * @return $this
     */
    public function doEmpty();
}