<?php
namespace frontend\modules\admin\components;

/**
 * Created by PhpStorm.
 * User: me
 * Date: 16.09.2014
 * Time: 14:09
 */

use frontend\components\FrontendController;

class AdminBaseController extends FrontendController
{
    public $filter_params = [];

    public function beforeAction($action)
    {
        return parent::beforeAction($action);
    }
} 