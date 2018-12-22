<?php
/**
 * Basic "kitchen sink" controller for frontend.
 * It was configured to be accessible by `/site` route, not the `/frontendSite` one!
 *
 * @package YiiBoilerplate\Frontend
 */
use \frontend\components\BaseController;

class FrontendBaseController extends BaseController
{
    public function filters()
    {
        return [
            'ajaxOnly + all',
        ];
    }

    /**
     * Actions attached to this controller
     *
     * @return array
     */
    public function actions()
    {
        return [
            'login' => ['class' => 'frontend\controllers\actions\base\LoginAction'],
            'logout' => ['class' => 'frontend\controllers\actions\base\LogoutAction'],
        ];
    }
}