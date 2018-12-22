<?php
/**
 * Basic "kitchen sink" controller for frontend.
 * It was configured to be accessible by `/site` route, not the `/frontendSite` one!
 *
 * @package YiiBoilerplate\Frontend
 */
use \frontend\components\FrontendController;

class FrontendSiteController extends FrontendController
{
    public function filters()
    {
        return [
            'ajaxOnly + all, tennis, basketball, hokkey',
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
            'error' => ['class' => 'SimpleErrorAction'],
            'fix' => ['class' => 'frontend\controllers\actions\site\FixAction'],
            'cache' => ['class' => 'frontend\controllers\actions\site\CacheAction'],
            'all' => ['class' => 'frontend\controllers\actions\site\AllAction'],
            'info' => ['class' => 'frontend\controllers\actions\site\InfoAction'],
            'faq' => ['class' => 'frontend\controllers\actions\site\FaqAction'],

            'index' => ['class' => 'frontend\controllers\actions\site\sport\FootballAction'],
            'tennis' => ['class' => 'frontend\controllers\actions\site\sport\TennisAction'],
            'basketball' => ['class' => 'frontend\controllers\actions\site\sport\BasketballAction'],
            'hokkey' => ['class' => 'frontend\controllers\actions\site\sport\HokkeyAction'],
        ];
    }
}