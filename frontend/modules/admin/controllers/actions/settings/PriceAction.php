<?php
namespace frontend\modules\admin\controllers\actions\settings;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use Yii;

class PriceAction extends CAction
{
    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        $price_type = Yii::app()->getRequest()->getParam('price_type');
        $post = Yii::app()->getRequest()->getPost('PriceSettings');
        if (!$post)
            Yii::app()->getAjax()->addErrors('Некорректные данные')->send();

        $criteria = new \CDbCriteria();
        $criteria->addCondition('price_id = :price_id');
        $criteria->params = [':price_id' => $price_type];
        $Price = \PriceSettings::model()->find($criteria);
        if (!$Price)
            $Price = new \PriceSettings();

        $Price->scenario = 'updateAction';
        $Price->setAttributes($post);
        $Price->onAfterChange = [new \AdminHistory(), 'afterChange'];
        if ($Price->updateAction())
            Yii::app()->getAjax()->addMessage('Настройки обновленны');
        else
            Yii::app()->getAjax()->addErrors($Price);

        $params = [
            'price' => $Price,
        ];
        Yii::app()->getAjax()->addReplace('price_form', '#content-replacement #settings-price-' . $Price->getPriceId(), $params)
            ->send();
    }
}