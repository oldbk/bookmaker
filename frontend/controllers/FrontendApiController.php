<?php
/**
 * Basic "kitchen sink" controller for frontend.
 * It was configured to be accessible by `/site` route, not the `/frontendSite` one!
 *
 * @package YiiBoilerplate\Frontend
 */
use \frontend\components\BaseController;

class FrontendApiController extends BaseController
{
    public function filters()
    {
        return [
            //'ajaxOnly + images, online',
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
            'auth' => ['class' => 'frontend\controllers\actions\api\AuthAction'],
        ];
    }
}