<?php
namespace common\components;
/**
 * Created by PhpStorm.
 * User: me
 * Date: 12.02.2015
 * Time: 20:25
 */

use Exception;
class NException extends Exception
{
    const ERROR_TRANSFER        = 800;

    const ERROR_RESULT          = 900;
    const ERROR_RESULT_GET      = 901;
    const ERROR_RESULT_EVENT    = 902;
    const ERROR_RESULT_RATIO    = 903;
    const ERROR_RESULT_MONEY    = 904;

    const ERROR_PARSE           = 1000;

    const ERROR_USER_REFUND     = 1100;

    const ERROR_BET             = 1200;
    const ERROR_BET_ORDINAR     = 1201;
    const ERROR_BET_EXPRESS     = 1202;

    const ERROR_EVENT           = 1300;
    const ERROR_EVENT_RESULT    = 1301;

    const ERROR_FINANCE_IN          = 1400;
    const ERROR_FINANCE_IN_VOUCHER  = 1401;
    const ERROR_FINANCE_IN_EKR      = 1402;
    const ERROR_FINANCE_IN_KR       = 1403;
    const ERROR_FINANCE_OUT         = 1404;
    const ERROR_FINANCE_OUT_VOUCHER = 1405;
    const ERROR_FINANCE_OUT_EKR     = 1406;
    const ERROR_FINANCE_OUT_KR      = 1407;

    const ERROR_STATS               = 1500;

    const ERROR_USER                = 1600;
    const ERROR_USER_CREATE         = 1601;
    const ERROR_USER_UPDATE         = 1602;

    const ERROR_PAYMENT             = 1700;

    const ERROR_PROBLEM             = 1800;
    const ERROR_PROBLEM_DATE        = 1801;

    private $_params = [];

    public function __construct($message = "", $code = 0, $params = [], Exception $previous = null)
    {
        $this->_params = $params;
        parent::__construct($message, $code, $previous);
    }

    public function getParams()
    {
        return $this->_params;
    }
}