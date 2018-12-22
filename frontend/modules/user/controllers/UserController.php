<?php
namespace frontend\modules\user\controllers;

/**
 * Basic "kitchen sink" controller for frontend.
 * It was configured to be accessible by `/site` route, not the `/frontendSite` one!
 *
 * @package YiiBoilerplate\Frontend
 */

use frontend\modules\user\components\UserBaseController;

class UserController extends UserBaseController
{
    public function filters()
    {
        return array(
            'ajaxOnly + auto',
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
            'auto' => ['class' => 'frontend\modules\user\controllers\actions\user\AutoAction'],
        );
    }
}