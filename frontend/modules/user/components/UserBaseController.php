<?php
namespace frontend\modules\user\components;

/**
 * Created by PhpStorm.
 * User: me
 * Date: 16.09.2014
 * Time: 14:09
 */

use frontend\components\FrontendController;

class UserBaseController extends FrontendController
{
    public function beforeAction($action)
    {
        return parent::beforeAction($action);
    }
}