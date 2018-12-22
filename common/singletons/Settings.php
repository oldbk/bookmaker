<?php
/**
 * Created by PhpStorm.
 */

namespace common\singletons;


class Settings
{
    /** @var self */
    private static $_instance = null;
    /** @var \Settings */
    private $model = null;

    private function __construct() {}

    protected function __clone() {}

    public function import() {}

    public function get() {}

    /**
     * @return \Settings
     * @throws \Exception
     */
    public static function init()
    {
        if(is_null(self::$_instance)) {
            self::$_instance = new self();
            /** @var \Settings $model */
            $model = \Settings::model()->find();
            if($model)
                self::$_instance->setModel($model);
            else
                throw new \Exception('Некорректный запрос');
        }

        return self::$_instance->model();
    }

    /**
     * @return \Settings
     */
    public function model()
    {
        return $this->model;
    }

    /**
     * @param \Settings $model
     * @return $this
     */
    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    /**
     * @return \Settings
     * @deprecated
     */
    public function newModel()
    {
        $this->model = new \Settings();
        return $this->model;
    }
}