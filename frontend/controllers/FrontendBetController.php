<?php
/**
 * Basic "kitchen sink" controller for frontend.
 * It was configured to be accessible by `/site` route, not the `/frontendSite` one!
 *
 * @package YiiBoilerplate\Frontend
 */
use \frontend\components\FrontendController;

class FrontendBetController extends FrontendController
{
    public function filters()
    {
        return [
            'ajaxOnly + ordinar, express',
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
            'single'    => ['class' => 'frontend\controllers\actions\bet\OrdinarAction'],
            'express'   => ['class' => 'frontend\controllers\actions\bet\ExpressAction'],
            //'prepare'   => ['class' => 'frontend\controllers\actions\bet\PrepareAction'],
            'info'      => ['class' => 'frontend\controllers\actions\bet\InfoAction'],
        ];
    }

    public function beforeAction($action)
    {
        $r = parent::beforeAction($action);

        $isDailyLimit = false;
        /** @var Settings $Settings */
        $Settings = Settings::model()->find();
        if ($Settings)
            $isDailyLimit = $Settings->getIsDailyLimit();
        if ($isDailyLimit && Yii::app()->getUser()->getFreeDailyLimit() == 0)
            Yii::app()->ajax
                ->addErrors('У вас превышен дневной лимит в активной валюте. Попробуйте сделать ставку в другой валюте')
                ->send();

        return $r;
    }
}