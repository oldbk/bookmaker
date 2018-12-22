<?php
namespace frontend\controllers\actions\site;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use Yii;

class CacheAction extends CAction
{
    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        if (!Yii::app()->getUser()->isAdmin())
            Yii::app()->getAjax()->addErrors('Страница не найдена')->send();

        Yii::app()->getCache()->flush();
    }
}