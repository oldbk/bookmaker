<?php
namespace frontend\modules\admin\controllers\actions\settings;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use common\interfaces\iPrice;
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
        $model = \Settings::model()->find();
        if (!$model)
            $model = new \Settings();

        $criteria = new \CDbCriteria();
        $criteria->index = 'price_id';
        $Prices = \PriceSettings::model()->findAll($criteria);
        foreach ([iPrice::TYPE_EKR, iPrice::TYPE_KR, iPrice::TYPE_GOLD] as $price_type) {
            if (!isset($Prices[$price_type]))
                $Prices[$price_type] = new \PriceSettings();
        }

        $params = [
            'model' => $model,
            'prices' => $Prices,
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