<?php
namespace frontend\modules\user\controllers\actions\finance\out;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use common\factories\TransferFactory;
use common\helpers\Convert;
use common\interfaces\iPrice;
use common\singletons\prices\PricesKR;
use Yii;

class KrAction extends CAction
{
    public $admin_part = false;

    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        $form = Yii::app()->getRequest()->getPost('krOut', []);
        if (!isset($form['price']) || $form['price'] <= 0)
            Yii::app()->getAjax()->addErrors('Некорректная цена')->send();

        $t = Yii::app()->getDb()->beginTransaction();
        try {
            /** @var \User $User */
            $User = \User::model()->findByPk(\Yii::app()->getUser()->getId());

            $price = Convert::getMoneyFormat($form['price']);
            if ($User->getKrBalance() < $price) {
                Yii::app()->getAjax()->addErrors('Недостаточно средств');
                throw new \Exception();
            }

            if (($price <= PricesKR::init()->getStrangeOutput() || PricesKR::init()->getStrangeOutput() == 0) && (PricesKR::init()->isAutoOutput() && Yii::app()->checker->check($User->getId(), iPrice::TYPE_KR))) {
                $Output = TransferFactory::factory('io', iPrice::TYPE_KR, [$User]);
                if (!$Output->take($price)) {
                    Yii::app()->getAjax()->addErrors('Не удалось вывести кр');
                    throw new \Exception();
                }

                Yii::app()->getAjax()->addMessage(sprintf('Вы успешно вывели кр в кол-ве: %s', $price));
            } else {
                $Moder = TransferFactory::factory('moder', iPrice::TYPE_KR, [$User]);
                if (!$Moder->run($price)) {
                    Yii::app()->getAjax()->addErrors('Не удалось вывести кр');
                    throw new \Exception();
                }

                Yii::app()->getAjax()->addMessage(sprintf('Вы успешно создали запрос на вывод кр в кол-ве: %s', $price));
            }

            $t->commit();
            $User->sendBalanceToFront();

            Yii::app()->getAjax()
                ->runJS('clearFinanceOut');
        } catch (\Exception $ex) {
            $t->rollback();
        }

        Yii::app()->getAjax()->send();
    }
}