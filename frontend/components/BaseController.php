<?php
namespace frontend\components;

/**
 * Base class for controllers at frontend.
 *
 * Includes all assets required for frontend and also registers Google Analytics widget if there's code specified.
 *
 * @package YiiBoilerplate\Frontend
 */


use common\components\Controller;
use Yii;

class BaseController extends Controller
{
    public $layout = 'main';

    /**
     * What to do before rendering the view file.
     *
     * We include Google Analytics code if ID was specified and register the frontend assets.
     *
     * @param string $view
     * @return bool
     */
    public function beforeRender($view)
    {
        $result = parent::beforeRender($view);
        $this->addGoogleAnalyticsCode();
        return $result;
    }

    private function addGoogleAnalyticsCode()
    {
        $gaid = @Yii::app()->params['google.analytics.id'];
        if ($gaid)
            $this->widget('frontend.widgets.GoogleAnalytics.GoogleAnalyticsWidget', compact('gaid'));
    }

    private function registerAssets()
    {

    }

    public function beforeAction($action)
    {
        $result = parent::beforeAction($action);
        $this->registerAssets();

        return $result;
    }
}
