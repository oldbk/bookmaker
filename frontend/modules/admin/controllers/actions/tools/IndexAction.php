<?php
namespace frontend\modules\admin\controllers\actions\tools;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use common\interfaces\iStatus;
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
        $Event = \SportEvent::model()->find();
        $params = [
            'Event' => $Event
        ];

        Yii::app()->getStatic()->setWww()
            ->registerScriptFile('private/tools.js', \CClientScript::POS_END, !YII_DEBUG);

        if (Yii::app()->getRequest()->getIsAjaxRequest())
            Yii::app()->getAjax()
                ->addTrigger('page:loaded')
                ->addReplace('index', '#content-replacement', $params)
                ->send();
        else
            $this->getController()->render('index', $params);
    }
}