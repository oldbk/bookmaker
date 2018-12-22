<?php
namespace frontend\modules\user\controllers;

/**
 * Basic "kitchen sink" controller for frontend.
 * It was configured to be accessible by `/site` route, not the `/frontendSite` one!
 *
 * @package YiiBoilerplate\Frontend
 */

use common\interfaces\iPrice;
use frontend\modules\user\components\UserBaseController;

class FinanceController extends UserBaseController
{
    public function filters()
    {
        return array(
            'ajaxOnly + balance, in, out, in_voucher, in_ekr, in_kr, out_voucher, out_ekr, out_kr',
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
            'balance' => ['class' => 'frontend\modules\user\controllers\actions\finance\BalanceAction'],
            'index' => ['class' => 'frontend\modules\user\controllers\actions\finance\IndexAction'],
            'in' => ['class' => 'frontend\modules\user\controllers\actions\finance\InAction'],
            'out' => ['class' => 'frontend\modules\user\controllers\actions\finance\OutAction'],

            'in_ekr' => ['class' => 'frontend\modules\user\controllers\actions\finance\in\EkrAction'],
            'in_kr' => ['class' => 'frontend\modules\user\controllers\actions\finance\in\KrAction'],
            'in_gold' => ['class' => 'frontend\modules\user\controllers\actions\finance\in\GoldAction'],
            'out_ekr' => ['class' => 'frontend\modules\user\controllers\actions\finance\out\EkrAction'],
            'out_kr' => ['class' => 'frontend\modules\user\controllers\actions\finance\out\KrAction'],
            'out_gold' => ['class' => 'frontend\modules\user\controllers\actions\finance\out\GoldAction'],

            'cancel' . iPrice::TYPE_EKR => ['class' => 'frontend\modules\user\controllers\actions\finance\cancel\EkrAction'],
            'cancel' . iPrice::TYPE_KR => ['class' => 'frontend\modules\user\controllers\actions\finance\cancel\KrAction'],
            'cancel' . iPrice::TYPE_GOLD => ['class' => 'frontend\modules\user\controllers\actions\finance\cancel\GoldAction'],

        );
    }
}