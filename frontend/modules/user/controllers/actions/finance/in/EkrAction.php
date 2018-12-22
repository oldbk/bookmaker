<?php
namespace frontend\modules\user\controllers\actions\finance\in;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use common\factories\TransferFactory;
use common\helpers\Convert;
use common\interfaces\iPrice;
use common\singletons\Settings;
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
		if(!Yii::app()->getUser()->isAdmin()) {
			Yii::app()->getAjax()->addErrors("Пополнение счета временно приостановлено")
				->send();
			die;
		}

        $form = Yii::app()->getRequest()->getPost('ekrIn', []);
        if (!isset($form['price']) || $form['price'] <= 0)
            Yii::app()->getAjax()->addErrors('Некорректная цена')->send();

        $t = Yii::app()->getDb()->beginTransaction();
        try {
            $min_level = Settings::init()->getMinLevel();

            if (Yii::app()->getUser()->getLevel() < $min_level) {
                Yii::app()->getAjax()->addErrors("Ввод кр доступен с {$min_level} уровня");
                throw new \Exception();
            }

            if (Yii::app()->getUser()->getAlign() == 4) {
                Yii::app()->getAjax()->addErrors("Невозможнен ввод средств: склонность хаос.");
                throw new \Exception();
            }

            /** @var \User $User */
            $User = \User::model()->findByPk(\Yii::app()->getUser()->getId());
            $BankList = Yii::app()->getOldbk()->getBankInfo($User->getGameId());

            $price = Convert::getMoneyFormat($form['price']);
            if (!isset($form['bank']) || !isset($form['pass'])) {
                $params = [
                    'model' => $User,
                    'price' => $price,
                    'BankList' => $BankList,
                    'link' => Yii::app()->createUrl('/user/finance/in_ekr'),
                    'field' => 'ekrIn'
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

            if (!Yii::app()->getOldbk()->canTakeEkr($User->getGameId(), $price, $form['bank'])) {
                Yii::app()->getAjax()->addErrors('Недостаточно средств');
                throw new \Exception();
            }

            $Input = TransferFactory::factory('io', iPrice::TYPE_EKR, [$User, $form['bank']]);
            if (!$Input->add($price)) {
                Yii::app()->getAjax()->addErrors('Не удалось пополнить екр');
                throw new \Exception();
            }

            $t->commit();
            $User->sendBalanceToFront();

            Yii::app()->getAjax()
                ->addMessage('Вы успешно пополнили eкр')
                ->runJS('closeModal')
                ->runJS('clearFinanceIn');
        } catch (\Exception $ex) {
            $t->rollback();
        }

        Yii::app()->getAjax()->send();
    }
}