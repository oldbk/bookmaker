<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 16.09.2014
 * Time: 2:02
 */
namespace common\modules\user;

use CWebModule;
class UserModule extends CWebModule
{
    public $controllerNamespace = '\common\modules\user\controllers';

    public function init()
    {
        // this method is called when the module is being created
        // you may place code here to customize the module or the application

        // import the module-level models and components
        $this->setImport([
            'user.models.*',
            'user.components.*'
        ]);
    }

    public function beforeControllerAction($controller, $action)
    {
        if(parent::beforeControllerAction($controller, $action))
        {
            // this method is called before any module controller action is performed
            // you may place customized code here
            return true;
        }
        else
            return false;
    }
}