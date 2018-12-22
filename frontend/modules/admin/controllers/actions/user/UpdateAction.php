<?php
namespace frontend\modules\admin\controllers\actions\user;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use Yii;
use User;

class UpdateAction extends CAction
{
    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        $criteria = new \CDbCriteria();
        $criteria->addCondition('id = :id');
        $criteria->params = [':id' => Yii::app()->getRequest()->getParam('user_id')];
        /** @var User $User */
        $User = \User::model()->find($criteria);
        $User->scenario = 'updateAction';

        $post = Yii::app()->getRequest()->getPost('User');
        if ($post) {
            $User->setAttributes($post);
            $User->onAfterChange = [new \AdminHistory(), 'afterChange'];
            if (!$User->updateAction())
                Yii::app()->getAjax()->addErrors($User);
            else
                Yii::app()->getAjax()
                    ->runJS('closeUserEdit')
                    ->addMessage('Пользователь обновлен')->send();
        }

        $params = [
            'model' => $User,
        ];
        Yii::app()->getAjax()->addReplace('_form', '#content-replacement #replace-info-block', $params)
            ->send();
    }
}