<?php
namespace common\factories\parser\validators\_interface;
use common\factories\parser\parsers\_interface\iParser;

/**
 * Created by PhpStorm.
 */
interface iValidator
{
    /**
     * @return boolean
     */
    public function check();

    /**
     * @return iParser
     */
    public function getParser();

    /**
     * @return string
     */
    public function getTemplateName();
}