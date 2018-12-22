<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 16.09.2014
 * Time: 2:02
 */
namespace frontend\modules\admin;

use common\modules\admin\AdminModule as BaseAdminModule;

class AdminModule extends BaseAdminModule
{
    public $controllerNamespace = '\frontend\modules\admin\controllers';

    public function init()
    {
        // this method is called when the module is being created
        // you may place code here to customize the module or the application

        // import the module-level models and components
        $this->setImport([
            'admin.models.*',
            'admin.components.*',
        ]);
    }

    public function beforeControllerAction($controller, $action)
    {
        if (parent::beforeControllerAction($controller, $action)) {
            // this method is called before any module controller action is performed
            // you may place customized code here
            return true;
        } else
            return false;
    }
}