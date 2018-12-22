<?php
namespace frontend\modules\admin\controllers;

/**
 * Basic "kitchen sink" controller for frontend.
 * It was configured to be accessible by `/site` route, not the `/frontendSite` one!
 *
 * @package YiiBoilerplate\Frontend
 */

use common\helpers\Convert;
use frontend\modules\admin\components\AdminBaseController;

class OutputController extends AdminBaseController
{
    public function filters()
    {
        return array(//'ajaxOnly + accept, decline',
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
            'index' => ['class' => 'frontend\modules\admin\controllers\actions\output\IndexAction'],
            'accept' . Convert::TYPE_KR => ['class' => 'frontend\modules\admin\controllers\actions\output\accept\KrAction'],
            'accept' . Convert::TYPE_EKR => ['class' => 'frontend\modules\admin\controllers\actions\output\accept\EkrAction'],
            'decline' . Convert::TYPE_KR => ['class' => 'frontend\modules\admin\controllers\actions\output\decline\KrAction'],
            'decline' . Convert::TYPE_EKR => ['class' => 'frontend\modules\admin\controllers\actions\output\decline\EkrAction'],
            'out' => ['class' => 'frontend\modules\admin\controllers\actions\output\OutAction'],
        );
    }
}