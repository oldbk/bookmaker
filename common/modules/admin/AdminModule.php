<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 16.09.2014
 * Time: 2:02
 */
namespace common\modules\admin;

use CWebModule;
class AdminModule extends CWebModule
{
    public $controllerNamespace = '\common\modules\admin\controllers';

    public function init()
    {
        // this method is called when the module is being created
        // you may place code here to customize the module or the application

        // import the module-level models and components
        $this->setImport([
            'admin.models.*',
            'admin.components.*'
        ]);
    }

    public function beforeControllerAction($controller, $action)
    {
        if(parent::beforeControllerAction($controller, $action))
        {
            if(!\Yii::app()->getUser()->isAdmin())
                throw new \CHttpException(404, 'Страница не найдена');
            // this method is called before any module controller action is performed
            // you may place customized code here
            return true;
        }
        else
            return false;
    }
}