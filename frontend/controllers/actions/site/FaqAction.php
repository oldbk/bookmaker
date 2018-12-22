<?php
namespace frontend\controllers\actions\site;

use CAction;
use frontend\components\FrontendController;
use Yii;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 *
 * @method FrontendController getController()
 */
class FaqAction extends CAction
{
    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        $Page = \Pages::model()->find('dir = :dir', [':dir' => 'faq']);
        if (!$Page)
            Yii::app()->getAjax()->addErrors('Страница не найдена')->send();

        $params = ['model' => $Page];
        $this->getController()->setPageTitle('FAQ');
        if (Yii::app()->getRequest()->isAjaxRequest)
            Yii::app()->getAjax()
                ->addTrigger('page:loaded')
                ->addReplace('faq', '#content-replacement', $params)
                ->send();
        else
            $this->getController()->render('faq', $params);
    }
}