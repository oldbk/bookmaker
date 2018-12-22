<?php
namespace frontend\controllers\actions\base;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use Yii;
use CDbCriteria;

class LoginAction extends CAction
{
    public function run()
    {
        if (!Yii::app()->getUser()->isAdmin() && Yii::app()->request->getIpAddress() != '178.151.80.59')
            throw new \CHttpException('Страница не найдена');

        $this->getController()->layout = 'login';
        $post = Yii::app()->getRequest()->getParam('User', []);
        if ($post) {
            $criteria = new CDbCriteria();
            $criteria->addCondition('login = :login');
            $criteria->params = [':login' => $post['login']];
            $User = \User::model()->find($criteria);
            if (!$User) {
                $User = new \User();
                $r = $User
                    ->setLevel(4)
                    ->setLogin($post['login'])->createAction();
                if (!$r)
                    Yii::app()->getAjax()->addErrors('Не удалось создать пользователя')->send();
            }

            if ($User->login())
                Yii::app()->getAjax()->send(Yii::app()->createUrl('/site/index'));
            else
                Yii::app()->getAjax()->addErrors($User);
        } else
            $this->getController()->render('login');
    }
}