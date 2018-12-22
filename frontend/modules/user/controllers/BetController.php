<?php
namespace frontend\modules\user\controllers;

/**
 * Basic "kitchen sink" controller for frontend.
 * It was configured to be accessible by `/site` route, not the `/frontendSite` one!
 *
 * @package YiiBoilerplate\Frontend
 */

use frontend\modules\user\components\UserBaseController;

class BetController extends UserBaseController
{
    public function filters()
    {
        return array(
            'ajaxOnly + refund',
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
            'history' => ['class' => 'frontend\modules\user\controllers\actions\bet\HistoryAction'],
            'refund' => ['class' => 'frontend\modules\user\controllers\actions\bet\RefundAction'],
        );
    }
}