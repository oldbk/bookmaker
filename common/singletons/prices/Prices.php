<?php
namespace common\singletons\prices;
use Yii;
/**
 * Created by PhpStorm.
 */

class Prices
{
    /** @var Prices[] */
    private static $_instance = [];
    /** @var \PriceSettings */
    protected $model = null;

    private function __construct() {}

    protected function __clone() {}

    public function import() {}

    public function get() {}

    /**
     * @param null $price_type
     * @return \PriceSettings
     * @throws \Exception
     */
    public static function init($price_type = null)
    {
        if($price_type === null)
            $price_type = Yii::app()->getUser()->getAB();
        if(empty(self::$_instance)) {
            /** @var \PriceSettings[] $models */
            $models = \PriceSettings::model()->findAll();
            if(!$models)
                throw new \Exception('Валюты не настроены');

            foreach ($models as $model) {
                self::$_instance[$model->getPriceId()] = new self();
                self::$_instance[$model->getPriceId()]->setModel($model);
            }
        }

        if(!isset(self::$_instance[$price_type]))
            throw new \Exception('Данная валюта не найдена');

        return self::$_instance[$price_type]->model();
    }

    /**
     * @return \PriceSettings
     */
    public function model()
    {
        return $this->model;
    }

    /**
     * @param \PriceSettings $model
     * @return $this
     */
    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }
}