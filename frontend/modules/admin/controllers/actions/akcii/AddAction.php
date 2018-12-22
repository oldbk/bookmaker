<?php
namespace frontend\modules\admin\controllers\actions\akcii;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use Yii;

class AddAction extends CAction
{
    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        $Akcii = new \Akcii();
        $post = Yii::app()->request->getParam('Akcii');
        if ($post) {
            $Akcii->setAttributes($post);
            if (!$Akcii->save())
                Yii::app()->ajax->addErrors($Akcii)->send();

            Yii::app()->ajax->addMessage('Акция добавлена')
                ->runJS('updateGrid', ['akcii-list'])
                ->send();
        }
    }
}