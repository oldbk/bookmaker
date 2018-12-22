<?php
namespace frontend\modules\admin\controllers\actions\output;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use Yii;
use CActiveDataProvider;
use CDbCriteria;

class IndexAction extends CAction
{
    public $admin_part = false;

    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        $model = new \UserOutputRequest('search');
        $model->unsetAttributes();
        $post = Yii::app()->getRequest()->getParam('UserOutputRequest');
        if ($post)
            $model->setAttributes($post);

        $criteria = new CDbCriteria();
        $criteria->addCondition('`t`.status = :status');
        $criteria->with = ['user'];
        $criteria->params = [':status' => \UserOutputRequest::STATUS_NEW];

        $dataProvider = new CActiveDataProvider($model, [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => 50,
            ],
            'sort' => [
                'defaultOrder' => '`t`.id asc'
            ]
        ]);

        $params = [
            'dataProvider' => $dataProvider,
        ];
        if (Yii::app()->getRequest()->getIsAjaxRequest())
            Yii::app()->getAjax()
                ->addTrigger('page:loaded')
                ->addReplace('list', '#content-replacement', $params)
                ->send();
        else
            $this->getController()->render('list', $params);
    }
}