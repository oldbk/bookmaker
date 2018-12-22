<?php
/**
 * Basic "kitchen sink" controller for frontend.
 * It was configured to be accessible by `/site` route, not the `/frontendSite` one!
 *
 * @package YiiBoilerplate\Frontend
 */
use \frontend\components\FrontendController;

class FrontendLineController extends FrontendController
{
    public function filters()
    {
        return [
            'ajaxOnly + prepare',
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
            'football' => ['class' => 'frontend\controllers\actions\line\FootballAction'],
            'tennis' => ['class' => 'frontend\controllers\actions\line\TennisAction'],
            'basketball' => ['class' => 'frontend\controllers\actions\line\BasketballAction'],
            'hokkey' => ['class' => 'frontend\controllers\actions\line\HokkeyAction'],
            'events' => ['class' => 'frontend\controllers\actions\line\EventsAction'],
            'event' => ['class' => 'frontend\controllers\actions\line\EventAction'],
            'result' => ['class' => 'frontend\controllers\actions\line\ResultAction'],
            'all' => ['class' => 'frontend\controllers\actions\line\AllAction'],
        ];
    }
}