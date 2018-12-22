<?php
namespace frontend\modules\admin\controllers;

/**
 * Basic "kitchen sink" controller for frontend.
 * It was configured to be accessible by `/site` route, not the `/frontendSite` one!
 *
 * @package YiiBoilerplate\Frontend
 */

use frontend\modules\admin\components\AdminBaseController;

class EventController extends AdminBaseController
{
    public function filters()
    {
        return array(
            'ajaxOnly + accept, decline, control, close, refund, trash, ratio, deleteRatio, calc-betting',
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
            'all'           => ['class' => 'frontend\modules\admin\controllers\actions\event\AllAction'],
            'accept'        => ['class' => 'frontend\modules\admin\controllers\actions\event\AcceptAction'],
            'decline'       => ['class' => 'frontend\modules\admin\controllers\actions\event\DeclineAction'],
            'history'       => ['class' => 'frontend\modules\admin\controllers\actions\event\HistoryAction'],
            'control'       => ['class' => 'frontend\modules\admin\controllers\actions\event\ControlAction'],
            'close'         => ['class' => 'frontend\modules\admin\controllers\actions\event\CloseAction'],
            'bet'           => ['class' => 'frontend\modules\admin\controllers\actions\event\RatesAction'],
            'refund'        => ['class' => 'frontend\modules\admin\controllers\actions\event\RefundAction'],
            'trash'         => ['class' => 'frontend\modules\admin\controllers\actions\event\TrashAction'],
            'info'          => ['class' => 'frontend\modules\admin\controllers\actions\event\InfoAction'],
            'ratio'         => ['class' => 'frontend\modules\admin\controllers\actions\event\RatioAction'],
            'date'          => ['class' => 'frontend\modules\admin\controllers\actions\event\DateAction'],
            'resolve'       => ['class' => 'frontend\modules\admin\controllers\actions\event\ResolveAction'],
            'deleteRatio'   => ['class' => 'frontend\modules\admin\controllers\actions\event\DeleteRatioAction'],
            'all-betting'   => ['class' => 'frontend\modules\admin\controllers\actions\event\AllBettingAction'],
            'calc-betting'  => ['class' => 'frontend\modules\admin\controllers\actions\event\CalcBettingAction'],
        );
    }
}