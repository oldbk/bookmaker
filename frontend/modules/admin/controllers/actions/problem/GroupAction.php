<?php
namespace frontend\modules\admin\controllers\actions\problem;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use Yii;
use SportEvent;
use CActiveDataProvider;

class GroupAction extends CAction
{
    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        $SportEvent = new SportEvent('search');

        $criteria = new \CDbCriteria();
        $criteria->scopes = ['haveProblem'];
        $criteria->with = ['sport'];

        $dataProvider = new CActiveDataProvider($SportEvent, [
            'criteria' => $criteria,
            'pagination' => ['pageSize' => 20],
            'sort' => [
                'defaultOrder' => '`t`.date_int desc'
            ]
        ]);

        $params = [
            'dataProvider' => $dataProvider
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