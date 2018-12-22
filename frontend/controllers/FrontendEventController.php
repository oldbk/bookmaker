<?php
/**
 * Basic "kitchen sink" controller for frontend.
 * It was configured to be accessible by `/site` route, not the `/frontendSite` one!
 *
 * @package YiiBoilerplate\Frontend
 */
use \frontend\components\FrontendController;

class FrontendEventController extends FrontendController
{
    public function filters()
    {
        return [
            'ajaxOnly + hint',
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
            'hint' => ['class' => 'frontend\controllers\actions\event\HintAction'],
        ];
    }
}