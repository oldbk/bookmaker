<?php
namespace frontend\modules\user\controllers\actions\finance;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use common\helpers\Convert;
use common\singletons\prices\Prices;
use Yii;

class BalanceAction extends CAction
{
    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {

    	die;
        $type = Yii::app()->getRequest()->getParam('type');
        if (!Convert::checkBalanceType($type))
            Yii::app()->getAjax()->addErrors('Несуществующая валюта')->send();

        /** @var \Settings $Settings */
        /*$Settings = \Settings::model()->find();
        $min_level = $Settings ? $Settings->getMinLevel() : 8;

        if($type != \User::TYPE_KR && Yii::app()->user->getLevel() < $min_level)
            Yii::app()->ajax->addErrors("Эта валюта доступна с {$min_level} уровня")->send();*/

        /** @var \User $model */
        $model = Yii::app()->getUser()->model();
        $r = $model->setActiveBalance($type)->save();
        if (!$r)
            Yii::app()->getAjax()->addErrors($model);
        else {
            $label = Convert::getBalanceLabel($type);
            Yii::app()->getAjax()->addMessage("Установлена новая активная валюта {$label}");
        }

        Yii::app()->getAjax()
            ->menu()
            ->addTrigger('change:currency')
            ->addOther(['balance' => [
                'kr' => $model->getKrBalance(),
                'ekr' => $model->getEkrBalance(),
                'gold' => $model->getGoldBalance(),
                'active' => $type,
                'name' => Prices::init($type)->getShortName(),
            ]])
            ->send();
    }
}