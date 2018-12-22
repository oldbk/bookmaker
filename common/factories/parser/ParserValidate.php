<?php
/**
 * Created by PhpStorm.
 */

namespace common\factories\parser;


use common\components\NException;
use common\factories\parser\validators\_interface\iValidator;
use common\helpers\ReflectionClass;
use common\helpers\SportHelper;

class ParserValidate
{
    private static $validators = [
        SportHelper::SPORT_FOOTBALL_ID => [
            '\common\factories\parser\validators\football\Main',
            //'\common\factories\parser\validators\football\Statistic',
            //'\common\factories\parser\validators\football\Custom1',
            //'\common\factories\parser\validators\football\Custom2',
            //'\common\factories\parser\validators\football\Custom3',
            //'\common\factories\parser\validators\football\Custom4',
        ],
        SportHelper::SPORT_TENNIS_ID => [
            '\common\factories\parser\validators\tennis\Main',
            '\common\factories\parser\validators\tennis\Custom1',
        ],
        SportHelper::SPORT_BASKETBALL_ID => [
            '\common\factories\parser\validators\basketball\Main',
        ],
        SportHelper::SPORT_HOKKEY_ID => [
            '\common\factories\parser\validators\hokkey\Main',
        ]
    ];

    public static function getValidator($sport_id, $html)
    {
        foreach (self::$validators[$sport_id] as $className) {
            try {
                $r = new ReflectionClass($className, ['html' => $html]);
                /** @var iValidator $object */
                $object = $r->getClassInstance();
                if($object->check()) {
					return $object;
				}
            } catch (\Exception $ex) {
                throw new NException(sprintf('Неудалось найти класс. Class: %s', 'getParser', $className));
            }
        }

        return false;
    }
}