<?php
namespace common\singletons\prices;
use common\interfaces\iPrice;
/**
 * Created by PhpStorm.
 */

class PricesGold extends Prices
{
    private function __construct() {}

    /**
     * @param null $price_type
     * @return \PriceSettings
     * @throws \Exception
     */
    public static function init($price_type = null)
    {
        return parent::init(iPrice::TYPE_GOLD);
    }
}