<?php
namespace common\helpers;
use common\interfaces\iPrice;

/**
 * Created by PhpStorm.
 * User: me
 * Date: 12.02.2015
 * Time: 3:12
 */

class Convert implements iPrice
{
    /**
     * @param $value
     * @return float
     */
    public static function getMoneyFormat($value)
    {
        $value = str_replace(',', '.', $value);
        $value = strval($value * 100);
        return number_format(floor($value) / 100, 2, '.', '');
    }

    /**
     * @param $value
     * @param int $countAfterDot
     * @return string
     */
    public static function getFormat($value, $countAfterDot = 2)
    {
        $value = str_replace(',', '.', $value);
        $value = strval($value * 100);
        return number_format(floor($value) / 100, $countAfterDot, '.', '');
    }

    public static function roundMaxBet($value)
    {
        $value = floor($value) / 10;
        $intVal = floor($value);
        if($value - $intVal >= 0.5)
            $intVal += 0.5;

        return $intVal * 10;
    }

    private static $_balanceLabel = [
        self::TYPE_KR => 'кр',
        self::TYPE_EKR => 'екр',
        self::TYPE_GOLD => 'мон',
        //self::TYPE_VOUCHER => 'ваучеры',
    ];

    public static function checkBalanceType($type)
    {
        return isset(self::$_balanceLabel[$type]);
    }

    public static function getBalanceLabel($type)
    {
        return isset(self::$_balanceLabel[$type]) ? self::$_balanceLabel[$type] : null;
    }

    public static function getPriceType()
    {
        return self::$_balanceLabel;
    }
}