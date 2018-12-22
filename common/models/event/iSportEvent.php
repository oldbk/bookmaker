<?php

/**
 * Created by PhpStorm.
 */
interface iSportEvent
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return int
     */
    public function getDateInt();

    /**
     * @return int
     */
    public function getSportType();

    /**
     * @return int
     */
    public function getV();

    /**
     * @param $value
     * @return mixed
     */
    public function setV($value);

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @return boolean
     */
    public function canAuto();

    /**
     * @param $field
     * @return string|null
     */
    public function upDown($field);

    /**
     * @param $field
     * @return string|null
     */
    public function getFieldAlias($field);

    /**
     * @param $field
     * @return string|null
     */
    public function getFieldByAlias($field);

    /**
     * @return string|null
     * @deprecated
     */
    public function getViewFactoryTitle();

    /**
     * @return boolean
     */
    public function haveDiff();

    /**
     * @return \common\sport\ratio\_interfaces\iRatio
     */
    public function getNewRatio();
    /**
     * @return \common\sport\ratio\_interfaces\iRatio
     */
    public function getOldRatio();

    /**
     * @return \common\sport\result\iResult
     */
    public function getResult();

    /**
     * @param null $attributes
     * @param bool|true $clearErrors
     * @return boolean
     */
    public function validate($attributes = null, $clearErrors = true);

    /**
     * @return boolean
     */
    public function updateAction();

    /**
     * @param $name
     * @return mixed
     */
    public function getAttribute($name);

    /**
     * @param $field
     * @param $value
     * @return mixed
     */
    public function setAttribute($field, $value);

    /**
     * @return array
     */
    public function getAttributes();

    /**
     * @param bool|true $runValidation
     * @param null $attributes
     * @return boolean
     */
    public function save($runValidation = true, $attributes = null);

    /**
     * @return int
     */
    public function getSportId();

    /**
     * @return string
     */
    public function getTeam1();

    /**
     * @return string
     */
    public function getTeam2();

    /**
     * @return Sport
     */
    public function getSport();

    /**
     * @return int
     */
    public function getNumber();

    /**
     * @return int
     */
    public function getProblemCount();

    /**
     * @param $count
     * @return mixed
     */
    public function setProblemCount($count);

    /**
     * @param \common\sport\result\iResult $result
     * @return mixed
     */
    public function setResult($result);

    /**
     * @return array
     */
    public function getErrors();

    /**
     * @param int $status
     * @return $this
     */
    public function setStatus($status);

    /**
     * @param int $isHave
     * @return $this
     */
    public function setHaveResult($isHave);

    /**
     * @return $this
     */
    public function copy();

    /**
     * @return int
     */
    public function getHaveResult();

    /**
     * @return array
     */
    public function prepareForSocket();

    /**
     * @param $is_trash
     * @return $this
     */
    public function setIsTrash($is_trash);

    /**
     * @param $sport_type
     * @return $this
     */
    public function setSportType($sport_type);

    /**
     * @param $event_template
     * @return $this
     */
    public function setEventTemplate($event_template);

    /**
     * @param $relation_name
     * @return boolean
     */
    public function hasRelated($relation_name);

    /**
     * @return SportEventFixedValue[]
     */
    public function getRatioFixed();

    /**
     * @return boolean
     */
    public function getIsNewRecord();

    /**
     * @return mixed
     */
    public function getEventTypeView();

    /**
     * @return mixed
     */
    public function getEventTemplate();

    /**
     * @return int
     */
    public function getStatus();

    /**
     * @return int
     */
    public function getCnt();

    public function isNotAuto();

    /**
     * @param $not_auto
     * @return $this
     */
    public function setNotAuto($not_auto);

    public function getNotAutoReason();

    /**
     * @param $not_auto_reason
     * @return $this
     */
    public function setNotAutoReason($not_auto_reason);

    /**
     * @param $isProblem
     * @return self
     */
    public function setHaveProblem($isProblem);
}