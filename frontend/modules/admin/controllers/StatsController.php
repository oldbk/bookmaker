<?php
namespace frontend\modules\admin\controllers;

/**
 * Basic "kitchen sink" controller for frontend.
 * It was configured to be accessible by `/site` route, not the `/frontendSite` one!
 *
 * @package YiiBoilerplate\Frontend
 */

use frontend\modules\admin\components\AdminBaseController;

class StatsController extends AdminBaseController
{
    public function filters()
    {
        return array(
            'ajaxOnly + special, charts, top',
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
            'index' => ['class' => 'frontend\modules\admin\controllers\actions\stats\IndexAction'],
            'special' => ['class' => 'frontend\modules\admin\controllers\actions\stats\SpecialAction'],
            'charts' => ['class' => 'frontend\modules\admin\controllers\actions\stats\ChartsAction'],
            'top' => ['class' => 'frontend\modules\admin\controllers\actions\stats\TopAction'],
            'temp' => ['class' => 'frontend\modules\admin\controllers\actions\stats\TempAction'],
        );
    }
}