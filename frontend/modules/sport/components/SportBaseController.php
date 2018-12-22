<?php
namespace frontend\modules\sport\components;

/**
 * Created by PhpStorm.
 * User: me
 * Date: 16.09.2014
 * Time: 14:09
 */
use frontend\components\SportController;

class SportBaseController extends SportController
{
    public function beforeAction($action)
    {
        return parent::beforeAction($action);
    }
}