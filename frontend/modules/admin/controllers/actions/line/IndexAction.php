<?php
namespace frontend\modules\admin\controllers\actions\line;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use common\interfaces\iStatus;
use Yii;
use Sport;
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
        $criteria = new CDbCriteria();
        $criteria->order = '`t`.title asc';
        $criteria->with = ['newEventCount', 'allEventCount'];
        $criteria->addCondition('(select count(*) from sport_event fe where fe.have_problem = 0 and fe.is_trash = 0 and fe.sport_id = `t`.id and fe.status != :finish) > 0');
        $criteria->params = [':finish' => iStatus::STATUS_FINISH];

        $models = Sport::model()->findAll($criteria);

        $params = [
            'models' => $models
        ];

        Yii::app()->getUser()->setState('return_link', null);
        if (Yii::app()->getRequest()->isAjaxRequest)
            Yii::app()->getAjax()
                ->addTrigger('page:loaded')
                ->addReplace('list', '#content-replacement', $params)
                ->send();
        else
            $this->getController()->render('list', $params);
    }
}