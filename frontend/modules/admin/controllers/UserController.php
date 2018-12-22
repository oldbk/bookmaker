<?php
namespace frontend\modules\admin\controllers;

/**
 * Basic "kitchen sink" controller for frontend.
 * It was configured to be accessible by `/site` route, not the `/frontendSite` one!
 *
 * @package YiiBoilerplate\Frontend
 */

use frontend\modules\admin\components\AdminBaseController;

class UserController extends AdminBaseController
{
    public function filters()
    {
        return array(
            'ajaxOnly + update, in, out',
        );
    }

    /**
     * Actions attached to this controller
     *
     * @return array
     */
    public function actions()
    {
        return array(
            'index' => ['class' => 'frontend\modules\admin\controllers\actions\user\IndexAction'],
            'update' => ['class' => 'frontend\modules\admin\controllers\actions\user\UpdateAction'],
            'in' => ['class' => 'frontend\modules\admin\controllers\actions\user\InAction'],
            'out' => ['class' => 'frontend\modules\admin\controllers\actions\user\OutAction'],
        );
    }
}