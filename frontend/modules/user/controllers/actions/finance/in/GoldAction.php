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

class GoldAction extends CAction
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

        $t = Yii::app()->getDb()->beginTransaction();
        try {
            $min_level = Settings::init()->getMinLevel();

            if (Yii::app()->getUser()->getLevel() < $min_level) {
                Yii::app()->getAjax()->addErrors("Ввод монет доступен с {$min_level} уровня");
                throw new \Exception();
            }

            if (Yii::app()->getUser()->getAlign() == 4) {
                Yii::app()->getAjax()->addErrors("Невозможнен ввод средств: склонность хаос.");
                throw new \Exception();
            }

            /** @var \User $User */
            $User = \User::model()->findByPk(\Yii::app()->getUser()->getId());

            $form = Yii::app()->getRequest()->getPost('goldIn', []);
            if (!isset($form['price']) || $form['price'] <= 0) {
                Yii::app()->getAjax()->addErrors('Некорректная цена');
                throw new \Exception();
            }

            $price = Convert::getMoneyFormat($form['price']);
            Yii::app()->getAjax()->addOther(['price' => $price]);
            if (!Yii::app()->getOldbk()->canTakeGold($User->getGameId(), $price)) {
                Yii::app()->getAjax()->addErrors('Недостаточно средств');
                throw new \Exception();
            }

            $Input = TransferFactory::factory('io', iPrice::TYPE_GOLD, [$User]);
            if (!$Input->add($price)) {
                Yii::app()->getAjax()->addErrors('Не удалось пополнить монеты');
                throw new \Exception();
            }

            $t->commit();

            $User->sendBalanceToFront();

            Yii::app()->getAjax()
                ->addMessage('Вы успешно пополнили монеты')
                ->runJS('closeModal')
                ->runJS('clearFinanceIn');
        } catch (\Exception $ex) {
            $t->rollback();
        }

        Yii::app()->getAjax()->send();
    }
}