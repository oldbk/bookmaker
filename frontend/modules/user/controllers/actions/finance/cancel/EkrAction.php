<?php
namespace frontend\modules\user\controllers\actions\finance\cancel;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use common\components\NException;
use common\factories\TransferFactory;
use common\interfaces\iPrice;
use common\interfaces\iStatus;
use Yii;
use CDbCriteria;

class EkrAction extends CAction
{
    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        $t = Yii::app()->getDb()->beginTransaction();
        try {
            $criteria = new CDbCriteria();
            $criteria->addCondition('`t`.user_id = :user_id');
            $criteria->addCondition('`t`.balance_id = :balance_id');
            $criteria->addCondition('`t`.status = :new');
            $criteria->params = [
                ':user_id' => Yii::app()->getUser()->getId(),
                ':balance_id' => Yii::app()->getRequest()->getParam('balance_id'),
                ':new' => iStatus::STATUS_NEW
            ];

            /** @var \UserOutputRequest $Request */
            $Request = \UserOutputRequest::model()->find($criteria);
            if (!$Request) {
                Yii::app()->getAjax()->addErrors('Не удалось найти запрос');
                throw new \Exception();
            }

            $User = $Request->user;

            $Request
                ->setStatus(\UserOutputRequest::STATUS_DECLINE);
            if (!$Request->save()) {
                Yii::app()->getAjax()->addErrors($Request);
                throw new \Exception();
            }

            $Moder = TransferFactory::factory('moder', iPrice::TYPE_EKR, [$User, $Request->getBankid()]);
            if (!$Moder->cancel($Request->getBalanceId()))
                throw new NException("Не удалось вернуть eкр", NException::ERROR_FINANCE_IN);

            $t->commit();
            $User->sendBalanceToFront();

            Yii::app()->getAjax()
                ->runJS('closeModal')
                ->runJS('clearFinanceOut')
                ->addMessage('Вывод отозван');
        } catch (\Exception $ex) {
            $t->rollback();
            \MException::logMongo($ex);
        }

        Yii::app()->getAjax()->send();
    }
}