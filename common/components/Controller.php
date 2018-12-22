<?php
namespace common\components;

use CController;
use common\extensions\behaviors\menu\MenuBehaviors;
use Yii;

/**
 * Class Controller
 *
 * @property MenuBehaviors $menu
 */
class Controller extends CController
{
    public $layout =  'main';
    public $pageHead;
    public $pageTitle;

    public function beforeRender($view)
    {
        $result = parent::beforeRender($view);

        return $result;
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['menu'] = [
            'class' => '\common\extensions\behaviors\menu\MenuBehaviors',
        ];

        return $behaviors;
    }

    public function setPageTitle($title)
    {
        $this->pageTitle = Yii::app()->name.' - '.$title;
    }

    public function getPageTitle()
    {
        return $this->pageTitle;
    }

    public function hasPageTitle()
    {
        return $this->pageTitle !== null;
    }

    public function getViewPath()
    {
        $moduleId = $this->getModule();
        if(null !== $moduleId)
            $moduleId = $moduleId->getId();
        $controllerId = $this->getId();

        $viewPath = \Yii::app()->theme->basePath.DIRECTORY_SEPARATOR;
        if(null === $moduleId)
            $viewPath .= DIRECTORY_SEPARATOR."www";
        else
            $viewPath .= DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR.$moduleId;
        $viewPath .= DIRECTORY_SEPARATOR.$controllerId;

        return $viewPath;
    }

    public function getLayoutFile($layoutName)
    {
        $layout = \Yii::app()->theme->basePath.DIRECTORY_SEPARATOR."layouts";
        $t = $this->resolveViewFile($layoutName, $layout, $this->getViewPath());
        return $t;
    }

    public function beforeAction($action)
    {
        Yii::app()->getClientScript()->scriptMap = [
            'jquery.js' => false,
            'jquery.min.js' => false,
            'jquery-ui.css' => false,
            'jquery.yiigridview.js' => false,
            'jquery-ui.min.js' => false,
            'jquery-ui-no-conflict.min.js' => false
        ];

        if(defined('YII_DEBUG') && YII_DEBUG && !Yii::app()->getRequest()->getIsAjaxRequest()) {
            Yii::app()->getAssetManager()->forceCopy = true;
        }

        $result = parent::beforeAction($action);
        $this->registerAssets();

        if ($this->layout != 'error410')
            $this->menu()->buildMenu($action);

        return $result;
    }

    public function getViewFile($viewName)
    {
        if(($theme=Yii::app()->getTheme())!==null && ($viewFile=$theme->getViewFile($this,$viewName))!==false)
            return $viewFile;
        $moduleViewPath=$basePath=Yii::app()->getViewPath();
        if(($module=$this->getModule())!==null)
            $moduleViewPath=$module->getViewPath();
        return $this->resolveViewFile($viewName,$this->getViewPath(),$basePath,$moduleViewPath);
    }

    private function registerAssets()
    {
        Yii::app()->getStatic()->setWww()
            ->registerScriptFile('ajax.js', \CClientScript::POS_END, !YII_DEBUG)
            ->registerScriptFile('public/js/events.js', \CClientScript::POS_END, !YII_DEBUG)
            ->registerCssFile('style.css', !YII_DEBUG)
            ->registerCssFile('ajax.css', !YII_DEBUG)
            ->registerCssFile('main.css', !YII_DEBUG);

        Yii::app()->getStatic()->setLibrary('history')
            ->registerScriptFile('jquery.history.js', \CClientScript::POS_END);
        Yii::app()->getStatic()->setLibrary('storage')
            ->registerScriptFile('jquery.storageapi.min.js');

        if(Yii::app()->getUser()->isAdmin()) {
            Yii::app()->getStatic()->setWww()
                ->registerCssFile('private/admin.css', !YII_DEBUG)
                ->registerScriptFile('private/admin.js', \CClientScript::POS_END, !YII_DEBUG)
                ->registerScriptFile('private/event.js', \CClientScript::POS_END, !YII_DEBUG)
                ->registerScriptFile('private/socket.js', \CClientScript::POS_END, !YII_DEBUG);
        }
    }

    public function menu()
    {
        return $this->menu;
    }
} 