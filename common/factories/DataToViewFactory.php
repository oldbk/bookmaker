<?php
/**
 * Created by PhpStorm.
 */

namespace common\factories;

use common\components\NException;
use common\factories\data_to_view\_interface\iDataToView;
use common\helpers\ReflectionClass;
use common\helpers\SportHelper;

class DataToViewFactory extends BaseFactory
{
    /**
     * @param $sport_factory_id
     * @param array $factory_params
     * @return iDataToView
     * @throws NException
     */
    public static function factory($sport_factory_id, $factory_params = [])
    {
        $factory_name = SportHelper::getByID($sport_factory_id);
        $className = sprintf('\common\factories\data_to_view\DTV%s', self::prepareClassName($factory_name));
        try {
            $r = new ReflectionClass($className, $factory_params);
            $object = $r->getClassInstance();

            return $object;
        } catch (\Exception $ex) {
            throw new NException(sprintf('Неудалось найти класс в фабрике %s. Class: %s', 'DataToViewFactory', $className));
        }
    }
}