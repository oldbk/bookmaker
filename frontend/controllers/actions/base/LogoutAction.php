<?php
namespace frontend\controllers\actions\base;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use Yii;

class LogoutAction extends CAction
{
    public function run()
    {
        //if(!Yii::app()->user->isAdmin())
        //    Yii::app()->ajax->addErrors('Страница не найдена')->send();

        Yii::app()->getUser()->logout();
        $this->controller->redirect('/site/index');
        //Yii::app()->ajax->send(Yii::app()->createUrl('/site/index'));
    }
}