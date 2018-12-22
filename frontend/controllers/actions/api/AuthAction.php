<?php
namespace frontend\controllers\actions\api;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use common\components\NException;
use common\components\VarDumper;
use Yii;
use MException;
use User;
use UserIdentity;

class AuthAction extends CAction
{
    public function run()
    {
        try {
            Yii::app()->getUser()->logout();
            $uid = Yii::app()->getRequest()->getPost('uid');
            $hash = Yii::app()->getRequest()->getPost('hash');
            if (!$uid || !$hash || !Yii::app()->getOldbk()->checkIsAuth($uid, $hash))
                throw new \CHttpException(404, 'Страница не найдена');

            $OldbkUser = Yii::app()->getOldbk()->checkUser($uid);
            if ($OldbkUser === false)
                throw new \CHttpException(404, 'Страница не найдена');

            if ($OldbkUser->isBlock())
                throw new \CHttpException(404, 'Персонаж заблокирован');

            $criteria = new \CDbCriteria();
            $criteria->addCondition('game_id = :game_id');
            $criteria->params = [':game_id' => $OldbkUser->getGameId()];
            $User = User::model()->find($criteria);
            if (!$User)
                $User = new User();

            $User->setGameId($OldbkUser->getGameId())
                ->setAlign($OldbkUser->getAlign())
                ->setKlan($OldbkUser->getKlan())
                ->setLogin($OldbkUser->getLogin())
                ->setLevel($OldbkUser->getLevel());

            $errorCode = null;
            if ($User->getIsNewRecord()) {
                $r = $User->createAction();
                $errorCode = NException::ERROR_USER_CREATE;
            } else {
                $r = $User->updateAction();
                $errorCode = NException::ERROR_USER_UPDATE;
            }

            if (!$r)
                throw new NException('Обновить пользователя', $errorCode,
                    ['errors' => $User->getErrors(), 'attr' => $User->getAttributes()]);
            if ($User->login())
                $this->controller->redirect(['/site/index']);
            else {
                echo '<pre>';
                var_dump($User->getErrors());
                die;
            }
        } catch (\Exception $ex) {
            MException::logMongo($ex, 'user');
            if($uid == 546433)
            {
                VarDumper::dump($ex);die;
            }
            $this->controller->redirect('http://oldbk.com');
        }
    }
}