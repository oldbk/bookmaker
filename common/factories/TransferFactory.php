<?php
/**
 * Created by PhpStorm.
 */

namespace common\factories;

use common\components\NException;
use common\factories\transfer\_base\kr_ekr\Base;
use common\factories\transfer\_interface\iBalance;
use common\factories\transfer\_interface\iBetPayment;
use common\factories\transfer\_interface\iIO;
use common\factories\transfer\_interface\iModer;
use common\helpers\ReflectionClass;
use common\interfaces\iPrice;

class TransferFactory extends BaseFactory
{
    private static $_mapping = [
        iPrice::TYPE_KR => 'kr',
        iPrice::TYPE_EKR => 'ekr',
        iPrice::TYPE_GOLD => 'gold'
    ];

    /**
     * @param string $factory_name
     * @param string $price_type
     * @param array $factory_params
     * @return iBetPayment|iBalance|iIO|iModer|Base
     * @throws NException
     */
    public static function factory($factory_name, $price_type, $factory_params = [])
    {
        if(!isset(self::$_mapping[$price_type]))
            throw new NException(sprintf('Неудалось найти валюту в фабрике %s. Тип: %s', 'TransferFactory',$price_type));

        $dir = self::$_mapping[$price_type];

        $className = sprintf('\common\factories\transfer\\%s\\%s', $dir, self::prepareClassName($factory_name));
        try {
            $r = new ReflectionClass($className, $factory_params);
            $object = $r->getClassInstance();

            return $object;
        } catch (\Exception $ex) {
            throw new NException(sprintf('Неудалось найти класс в фабрике %s. Class: %s', 'TransferFactory', $className));
        }
    }
}