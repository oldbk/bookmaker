<?php
namespace frontend\modules\admin\controllers;

/**
 * Basic "kitchen sink" controller for frontend.
 * It was configured to be accessible by `/site` route, not the `/frontendSite` one!
 *
 * @package YiiBoilerplate\Frontend
 */

use frontend\modules\admin\components\AdminBaseController;

class TrashController extends AdminBaseController
{
    public function filters()
    {
        return array(
            'ajaxOnly + recovery',
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
            'index' => ['class' => 'frontend\modules\admin\controllers\actions\trash\IndexAction'],
            'recovery' => ['class' => 'frontend\modules\admin\controllers\actions\trash\RecoveryAction'],
        );
    }
}