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

class SportController extends FrontendController
{
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

        return $result;
    }

    public function beforeAction($action)
    {
        $result = parent::beforeAction($action);

        return $result;
    }
}
