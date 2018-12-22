<?php
/**
 * Created by PhpStorm.
 */

namespace common\sport\ratio\_interfaces;


interface iRatioHomepage
{
    /**
     * @return float
     */
    public function getRatioP1();

    /**
     * @return float
     */
    public function getRatioX();

    /**
     * @return float
     */
    public function getRatioP2();

}