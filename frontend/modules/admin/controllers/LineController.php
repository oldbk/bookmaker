<?php
namespace frontend\modules\admin\controllers;

/**
 * Basic "kitchen sink" controller for frontend.
 * It was configured to be accessible by `/site` route, not the `/frontendSite` one!
 *
 * @package YiiBoilerplate\Frontend
 */

use frontend\modules\admin\components\AdminBaseController;

class LineController extends AdminBaseController
{
    public function filters()
    {
        return array(
            'ajaxOnly + filter',
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
            'index' => ['class' => 'frontend\modules\admin\controllers\actions\line\IndexAction'],
            'events' => ['class' => 'frontend\modules\admin\controllers\actions\line\EventsAction'],
            'filter' => ['class' => 'frontend\modules\admin\controllers\actions\line\FilterAction'],
        );
    }
}