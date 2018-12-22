<?php
/**
 * Created by PhpStorm.
 */

namespace common\factories;

use common\components\NException;
use common\factories\problem_event\_default\_interface\iProblemEvent;
use common\helpers\ReflectionClass;
use common\helpers\SportHelper;

class ProblemEventFactory extends BaseFactory
{
    /**
     * @param $sport_factory_id
     * @param $sport_factory_name
     * @param array $factory_params
     * @return iProblemEvent
     * @throws NException
     */
    public static function factory($sport_factory_id, $sport_factory_name, $factory_params = [])
    {
        $factory_name = SportHelper::getByID($sport_factory_id);
        $className = sprintf('\common\factories\problem_event\\%s\\%s', $factory_name, self::prepareClassName($sport_factory_name));
        try {
            $r = new ReflectionClass($className, $factory_params);
            $object = $r->getClassInstance();

            return $object;
        } catch (\Exception $ex) {
            throw new NException(sprintf('Неудалось найти класс в фабрике %s. Class: %s', 'ProblemEventFactory', $className));
        }
    }
}