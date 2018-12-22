<?php
namespace frontend\modules\admin\controllers\actions\page;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use Yii;

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
        $criteria->params = [':id' => Yii::app()->getRequest()->getParam('id')];
        /** @var \Pages $Page */
        $Page = \Pages::model()->find($criteria);
        if (!$Page)
            Yii::app()->getAjax()->addErrors('Страница не найдена')->send();

        $post = Yii::app()->getRequest()->getParam('Pages');
        if ($post) {
            $Page->setAttributes($post);
            $Page->save();

            $this->getController()->redirect('/admin/page/index');
        } else
            $this->getController()->render('form', ['model' => $Page]);
    }
}