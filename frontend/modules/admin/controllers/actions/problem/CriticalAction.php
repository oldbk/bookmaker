<?php
namespace frontend\modules\admin\controllers\actions\problem;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use Yii;

class CriticalAction extends CAction
{
    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        $dataProvider = new \EMongoDataProvider(new \Critical(), [
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => 'desc'
                ]
            ]
        ]);

        if (Yii::app()->getRequest()->isAjaxRequest)
            Yii::app()->getAjax()
                ->addTrigger('page:loaded')
                ->addReplace('critical', '#content-replacement', ['dataProvider' => $dataProvider])
                ->send();
        else
            $this->getController()->render('critical', ['dataProvider' => $dataProvider]);
    }
}