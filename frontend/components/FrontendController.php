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
use common\singletons\prices\Prices;
use Yii;

class FrontendController extends Controller
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
        Yii::app()->getNodeSocket()->registerClientScripts();

        Yii::app()->getClientScript()->registerPackage('bbq');

        Yii::app()->getStatic()->setWww()
            ->registerCssFile('main.css', !YII_DEBUG)
            ->registerCssFile('font-awesome.min.css')
            ->registerScriptFile('autoNumeric.js', \CClientScript::POS_END, !YII_DEBUG)
            ->registerScriptFile('script.js', \CClientScript::POS_END, !YII_DEBUG)
            ->registerScriptFile('socket.js', \CClientScript::POS_BEGIN, !YII_DEBUG)
            ->registerCssFile('pager.css', !YII_DEBUG);

        Yii::app()->getStatic()->setWidget('grid')
            ->registerScriptFile('gridview.js', \CClientScript::POS_END, !YII_DEBUG);

        Yii::app()->getStatic()->setLibrary('datepicker')
            ->registerScriptFile('bootstrap-datepicker.js', \CClientScript::POS_END, !YII_DEBUG)
            ->registerLangFile('bootstrap-datepicker.ru.js', \CClientScript::POS_END)
            ->registerCssFile('datepicker3.css', !YII_DEBUG);
        Yii::app()->getStatic()->setLibrary('highstock')
            ->registerScriptFile('highstock.js', \CClientScript::POS_END);
        Yii::app()->getStatic()->setLibrary('needim-noty')
            ->registerScriptFile('jquery.noty.packaged.min.js');

        Yii::app()->getStatic()->setLibrary('select2')
            ->registerCssFile('select2.min.css')
            ->registerScriptFile('select2.full.min.js');

        Yii::app()->getStatic()->setLibrary('sticky')
            ->registerScriptFile('jquery.sticky.js', \CClientScript::POS_END, !YII_DEBUG)
            ->registerCssFile('sticker.css', !YII_DEBUG);

        Yii::app()->getStatic()->setLibrary('html5boilerplate')
            ->registerCssFile('normalize.css', !YII_DEBUG)
            ->registerCssFile('main.css', !YII_DEBUG)
            ->registerScriptFile('plugins.js', \CClientScript::POS_END, !YII_DEBUG);

        Yii::app()->getStatic()->setLibrary('jquery')
            ->registerCssFile('jquery-ui-1.10.4.custom.min.css')
            ->registerScriptFile('jquery-ui-1.10.4.custom.min.js');

        Yii::app()->getStatic()->setLibrary('scroll')
            ->registerScriptFile('scrollTo.js');

        Yii::app()->getStatic()->setLibrary('infinitescroll')
            ->registerScriptFile('jquery.iscroll.js', \CClientScript::POS_END, !YII_DEBUG);

        Yii::app()->getStatic()->setWww()
            ->registerScriptFile('public/js/event.js', \CClientScript::POS_END, !YII_DEBUG)
            ->registerScriptFile('public/js/user.js', \CClientScript::POS_END, !YII_DEBUG);

        $script = '$user.setOptions("'.Yii::app()->getUser()->getAB().'", "'.Prices::init()->getShortName().'");';
        Yii::app()->getClientScript()->registerScript(uniqid(), $script, \CClientScript::POS_END);
    }

    public function beforeAction($action)
    {
        $result = parent::beforeAction($action);

        if (Yii::app()->getUser()->getIsGuest()) {
            if ($pk = Yii::app()->getUser()->isAdminIp(Yii::app()->getRequest()->getIpAddress())) {
                /** @var \User $User */
                $User = \User::model()->findByPk($pk);
                $User->login();
                $this->redirect(['/site/index']);
            } else {
                if(Yii::app()->getRequest()->getIsAjaxRequest())
                    Yii::app()->getAjax()->send('http://capitalcity.oldbk.com/book_auth.php');
                else
                    $this->redirect('http://capitalcity.oldbk.com/book_auth.php');
            }
        }

        if (Yii::app()->getUser()->isBlocked())
            throw new \CHttpException(410, 'Похоже, что у Вас нет доступа в букмекер.<br>Обратитесь в КО');

        $this->registerAssets();

        return $result;
    }
}
