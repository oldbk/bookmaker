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
use common\singletons\prices\PricesEKR;
use Yii;

class EkrAction extends CAction
{
    public $admin_part = false;

    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        $form = Yii::app()->getRequest()->getPost('ekrOut', []);
        if (!isset($form['price']) || $form['price'] <= 0)
            Yii::app()->getAjax()->addErrors('Некорректная цена')->send();

        $t = Yii::app()->getDb()->beginTransaction();
        try {
            /** @var \User $User */
            $User = \User::model()->findByPk(\Yii::app()->getUser()->getId());

            $BankList = Yii::app()->getOldbk()->getBankInfo($User->getGameId());

            $price = Convert::getMoneyFormat($form['price']);
            if ($User->getEkrBalance() < $price) {
                Yii::app()->getAjax()->addErrors('Недостаточно средств');
                throw new \Exception();
            }

            if (!isset($form['bank']) || !isset($form['pass'])) {
                $params = [
                    'model' => $User,
                    'price' => $price,
                    'BankList' => $BankList,
                    'link' => Yii::app()->createUrl('/user/finance/out_ekr'),
                    'field' => 'ekrOut'
                ];
                Yii::app()->getAjax()
                    ->runJS('openCustom')
                    ->addReplace('_ekr_bank', '#customModal #replacement', $params);
                throw new \Exception();
            }

            if (!isset($form['bank']) || $form['bank'] == 0) {
                Yii::app()->getAjax()->addErrors('Некорректный банковский счет');
                throw new \Exception();
            }
            if (!Yii::app()->getOldbk()->bankAuth($User->getGameId(), $form['bank'], $form['pass'])) {
                Yii::app()->getAjax()->addErrors('Неудалось авторизоваться в банке, проверьте данные');
                throw new \Exception();
            }

            if (($price <= PricesEKR::init()->getStrangeOutput() || PricesEKR::init()->getStrangeOutput() == 0) && (PricesEKR::init()->isAutoOutput() && Yii::app()->checker->check($User->getId(), iPrice::TYPE_EKR))) {
                $Output = TransferFactory::factory('io', iPrice::TYPE_EKR, [$User, $form['bank']]);
                if (!$Output->take($price)) {
                    Yii::app()->getAjax()->addErrors('Не удалось вывести екр');
                    throw new \Exception();
                }

                Yii::app()->getAjax()->addMessage(sprintf('Вы успешно вывели екр в кол-ве %s', $price));
            } else {
                $Moder = TransferFactory::factory('moder', iPrice::TYPE_EKR, [$User, $form['bank']]);
                if (!$Moder->run($price)) {
                    Yii::app()->getAjax()->addErrors('Не удалось вывести екр');
                    throw new \Exception();
                }

                Yii::app()->getAjax()->addMessage(sprintf('Вы успешно создали запрос на вывод екр в кол-ве %s', $price));
            }

            $t->commit();
            $User->sendBalanceToFront();

            Yii::app()->getAjax()
                ->runJS('clearFinanceOut')
                ->runJS('closeModal');
        } catch (\Exception $ex) {
            $t->rollback();
        }

        Yii::app()->getAjax()->send();
    }
}