<?php
namespace frontend\modules\admin\controllers;

/**
 * Basic "kitchen sink" controller for frontend.
 * It was configured to be accessible by `/site` route, not the `/frontendSite` one!
 *
 * @package YiiBoilerplate\Frontend
 */

use frontend\modules\admin\components\AdminBaseController;

class TeamController extends AdminBaseController
{
    public function filters()
    {
        return array(
            'ajaxOnly + accept, delete',
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
            'index' => ['class' => 'frontend\modules\admin\controllers\actions\team\IndexAction'],
            'accept' => ['class' => 'frontend\modules\admin\controllers\actions\team\AcceptAction'],
            'delete' => ['class' => 'frontend\modules\admin\controllers\actions\team\DeleteAction'],
        );
    }
}