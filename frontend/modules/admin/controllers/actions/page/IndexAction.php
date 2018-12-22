<?php
namespace frontend\modules\admin\controllers\actions\page;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use Yii;

class IndexAction extends CAction
{
    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        $post = Yii::app()->getRequest()->getParam('Pages');

        $Pages = new \Pages('search');
        $Pages->unsetAttributes();
        if ($post)
            $Pages->setAttributes($post);

        $criteria = new \CDbCriteria();
        $dataProvider = new \CActiveDataProvider('Pages', [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => 30
            ]
        ]);

        $params = [
            'dataProvider' => $dataProvider,
        ];

        if (Yii::app()->getRequest()->getIsAjaxRequest())
            Yii::app()->getAjax()
                ->addTrigger('page:loaded')
                ->addReplace('index', '#content-replacement', $params)
                ->send();
        else
            $this->getController()->render('index', $params);
    }
}