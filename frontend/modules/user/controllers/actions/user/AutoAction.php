<?php
namespace frontend\modules\user\controllers\actions\user;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use Yii;
use CDbCriteria;

class AutoAction extends CAction
{
    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        $returned = [];

        $criteria = new CDbCriteria();
        $criteria->addSearchCondition('login', Yii::app()->getRequest()->getParam('q'));

        $dependency = new \CDbCacheDependency('SELECT MAX(update_at) FROM {{user}}');
        $dependency->reuseDependentData = true;
        /** @var \User[] $Users */
        $Users = \User::model()->cache(3600, $dependency)->findAll($criteria);
        foreach ($Users as $User)
            $returned[] = [
                'label' => $User->getLogin(),
                'id' => $User->getId(),
                'value' => $User->getLogin()
            ];

        Yii::app()->getAjax()->addOther(['items' => $returned])->send();
    }
}