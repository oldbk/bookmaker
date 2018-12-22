<?php
/**
 * Basic "kitchen sink" controller for frontend.
 * It was configured to be accessible by `/site` route, not the `/frontendSite` one!
 *
 * @package YiiBoilerplate\Frontend
 */
use \frontend\components\FrontendController;

class FrontendAcController extends FrontendController
{
    public function filters()
    {
        return [
            'ajaxOnly + line',
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
            'line' => ['class' => '\frontend\controllers\actions\ac\LineAction'],
        ];
    }
}