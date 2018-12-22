<?php
namespace frontend\modules\admin\controllers\actions\line;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use Yii;

class FilterAction extends CAction
{
    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        Yii::app()->getUser()->setState('return_link', null);
        Yii::app()->getAjax()->send(Yii::app()->createUrl('/admin/line/index'));
    }
}