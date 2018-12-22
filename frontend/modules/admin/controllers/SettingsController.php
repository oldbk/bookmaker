<?php
namespace frontend\modules\admin\controllers;

/**
 * Basic "kitchen sink" controller for frontend.
 * It was configured to be accessible by `/site` route, not the `/frontendSite` one!
 *
 * @package YiiBoilerplate\Frontend
 */

use frontend\modules\admin\components\AdminBaseController;

class SettingsController extends AdminBaseController
{
    /**
     * Actions attached to this controller
     *
     * @return array
     */
    public function actions()
    {
        return array(
            'index' => ['class' => 'frontend\modules\admin\controllers\actions\settings\IndexAction'],
            'price' => ['class' => 'frontend\modules\admin\controllers\actions\settings\PriceAction'],
            'settings' => ['class' => 'frontend\modules\admin\controllers\actions\settings\SettingsAction'],
        );
    }
}