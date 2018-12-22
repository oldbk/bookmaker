<?php
namespace frontend\modules\admin\controllers;

/**
 * Basic "kitchen sink" controller for frontend.
 * It was configured to be accessible by `/site` route, not the `/frontendSite` one!
 *
 * @package YiiBoilerplate\Frontend
 */

use frontend\modules\admin\components\AdminBaseController;

class AkciiController extends AdminBaseController
{
    public function filters()
    {
        return array(
            'ajaxOnly + add, send, auto',
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
            'index' => ['class' => 'frontend\modules\admin\controllers\actions\akcii\IndexAction'],
            'add' => ['class' => 'frontend\modules\admin\controllers\actions\akcii\AddAction'],
            'send' => ['class' => 'frontend\modules\admin\controllers\actions\akcii\SendAction'],
            'auto' => ['class' => 'frontend\modules\admin\controllers\actions\akcii\AutoAction'],
        );
    }
}