<?php
namespace frontend\modules\admin\controllers;

/**
 * Basic "kitchen sink" controller for frontend.
 * It was configured to be accessible by `/site` route, not the `/frontendSite` one!
 *
 * @package YiiBoilerplate\Frontend
 */

use frontend\modules\admin\components\AdminBaseController;

class ToolsController extends AdminBaseController
{
    public function filters()
    {
        return array(
            'ajaxOnly + result, changeResult',
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
            'reload' => ['class' => 'frontend\modules\admin\controllers\actions\tools\ReloadAction'],
            'index' => ['class' => 'frontend\modules\admin\controllers\actions\tools\IndexAction'],

            //recalc event
            'result' => ['class' => 'frontend\modules\admin\controllers\actions\tools\recalc\ResultAction'],
            'changeResult' => ['class' => 'frontend\modules\admin\controllers\actions\tools\recalc\ChangeResultAction'],

            //simulator
            'simulator' => ['class' => 'frontend\modules\admin\controllers\actions\tools\simulator\SimulatorAction'],
            'checkSimulator' => ['class' => 'frontend\modules\admin\controllers\actions\tools\simulator\CheckAction'],
        );
    }
}