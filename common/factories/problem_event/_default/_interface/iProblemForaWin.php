<?php
/**
 * Created by PhpStorm.
 */

namespace common\factories\problem_event\_default\_interface;


interface iProblemForaWin
{
    /**
     * @return float
     */
    public function getForaVal1();
    /**
     * @return float
     */
    public function getForaVal2();
    /**
     * @return float
     */
    public function getForaRatio1();
    /**
     * @return float
     */
    public function getForaRatio2();
    /**
     * @return float
     */
    public function getRatioP1();
    /**
     * @return float
     */
    public function getRatioP2();
}