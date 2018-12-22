<?php
namespace frontend\modules\admin\controllers\actions\akcii;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use Yii;
use CDbCriteria;
use CActiveDataProvider;

class IndexAction extends CAction
{
    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        $Akcii = new \Akcii('search');
        $Akcii->unsetAttributes();
        $post = Yii::app()->request->getParam('Akcii');
        if ($post)
            $Akcii->setAttributes($post);

        $criteria = new CDbCriteria();

        $dataProvider = new CActiveDataProvider($Akcii, [
            'criteria' => $criteria,
            'pagination' => ['pageSize' => 20],
            'sort' => [
                'defaultOrder' => '`t`.id desc'
            ]
        ]);

        $params = [
            'model' => $Akcii,
            'dataProvider' => $dataProvider,
            'akciaForm' => new \AkciaForm()
        ];

        if (Yii::app()->request->isAjaxRequest)
            Yii::app()->ajax->addReplace('list', '#content-replacement', $params)
                ->runJS('select2Remote', ['#AkciaForm_akcia_id', Yii::app()->createUrl('/admin/akcii/auto')])
                ->runJS('select2Remote', ['#AkciaForm_user_id', Yii::app()->createUrl('/user/user/auto')])
                ->send();
        else
            $this->controller->render('list', $params);
    }
}