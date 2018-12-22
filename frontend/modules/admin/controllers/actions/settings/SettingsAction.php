<?php
namespace frontend\modules\admin\controllers\actions\settings;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use Yii;

class SettingsAction extends CAction
{
    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        $model = \Settings::model()->find();
        if (!$model)
            $model = new \Settings();

        $post = Yii::app()->getRequest()->getPost('Settings');
        if ($post) {
            $model->setAttributes($post);

            $model->onAfterChange = [new \AdminHistory(), 'afterChange'];
            if (!$model->updateAction())
                Yii::app()->getAjax()->addErrors($model);
            else
                Yii::app()->getAjax()->addMessage('Настройки обновленны');
        }

        $params = [
            'model' => $model,
        ];
        Yii::app()->getAjax()->addReplace('settings_form', '#content-replacement #settings-price', $params)
            ->send();
    }
}