<?php
namespace frontend\modules\admin\controllers\actions\problem;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use Yii;

class CriticalOkAction extends CAction
{
    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        $id = Yii::app()->getRequest()->getParam('critical_id');

        /** @var \Critical $model */
        $model = \Critical::model()->findByPk($id);
        $r = $model->setIsNew(false)
            ->save();
        if ($r) {
            Yii::app()->getAjax()
                ->addMessage('Ошибка просмотрена')
                ->runJS('updateGrid', ['critical-list']);
        } else {
            Yii::app()->getAjax()->addErrors($model)->send();
        }

        Yii::app()->getAjax()->send();
    }
}