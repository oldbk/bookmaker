<?php
/**
 * Created by PhpStorm.
 */

namespace common\factories\data_to_view\_interface;


interface iDataToView
{
    public function getByName($field_name);
    public function getResultLabel($field);
    public function buildMessageResultString($field);
    public function getTitle();

    public function getForaVal1();
    public function getForaVal2();

    public function getForaRatio1();
    public function getForaRatio2();

    public function getTotalVal();
    public function getTotalMore();
    public function getTotalLess();

    public function getRatioP1();
    public function getRatioX();
    public function getRatioP2();

    public function getRatio1x();
    public function getRatio12();
    public function getRatioX2();

    public function getITotalVal1();
    public function getITotalVal2();

    public function getITotalMore1();
    public function getITotalMore2();

    public function getITotalLess1();
    public function getITotalLess2();
}