<?php
namespace common\factories;
use common\components\NException;
use common\factories\ratio\_default\_interface\iRatio;
use common\helpers\ReflectionClass;
use common\helpers\SportHelper;

/**
 * Class RatioFactory
 * @package common\factories
 */
class RatioFactory extends BaseFactory
{
    /**
     * @param $sport_factory_id
     * @param $sport_factory_name
     * @param $factory_params
     * @return iRatio
     * @throws NException
     */
    public static function factory($sport_factory_id, $sport_factory_name, $factory_params = [])
    {
        $factory_name = SportHelper::getByID($sport_factory_id);
        $className = sprintf('\common\factories\ratio\\%s\\%s', $factory_name, self::prepareClassName($sport_factory_name));
        try {
            $r = new ReflectionClass($className, $factory_params);
            $object = $r->getClassInstance();

            return $object;
        } catch (\Exception $ex) {
            throw new NException(sprintf('Неудалось найти класс в фабрике %s. Class: %s', 'RatioFactory', $className));
        }
    }
}