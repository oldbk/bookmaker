<?php
/**
 * Created by PhpStorm.
 */

namespace common\factories;


use common\components\NException;
use common\factories\BaseFactory;
use common\factories\parser\results\_interfaces\iResult;
use common\helpers\ReflectionClass;
use common\helpers\SportHelper;

class ResultFactory extends BaseFactory
{
    /**
     * @param $factory_id
     * @param array $factory_params
     * @return iResult
     * @throws NException
     */
    public static function factory($factory_id, $factory_params = [])
    {
        $factory_name = SportHelper::getByID($factory_id);
        $className = '\common\factories\parser\results\\'.self::prepareClassName($factory_name);
        try {
            $r = new ReflectionClass($className, $factory_params);
            $object = $r->getClassInstance();

            return $object;
        } catch (\Exception $ex) {
            throw new NException(sprintf('Неудалось найти класс в фабрике %s. Class: %s', 'ResultFactory', $className));
        }
    }
}