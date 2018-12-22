<?php
namespace frontend\modules\admin\controllers\actions\output\accept;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use common\components\NException;
use common\factories\TransferFactory;
use common\interfaces\iPrice;
use Yii;
use CDbCriteria;

class EkrAction extends CAction
{
    public function run()
    {
        $t = Yii::app()->getDb()->beginTransaction();
        try {
            $criteria = new CDbCriteria();
            $criteria->addCondition('`t`.id = :id');
            $criteria->addCondition('`t`.price_type = :ekr');
            $criteria->with = ['user'];
            $criteria->params = [
                ':id' => Yii::app()->getRequest()->getParam('request_id'),
                ':ekr' => iPrice::TYPE_EKR
            ];
            /** @var \UserOutputRequest $Request */
            $Request = \UserOutputRequest::model()->find($criteria);
            if (!$Request) {
                Yii::app()->getAjax()->addErrors('Не удалось найти запрос');
                throw new \Exception();
            }

            $User = $Request->user;

            $Request
                ->setModeratorId(Yii::app()->getUser()->getId())
                ->setStatus(\UserOutputRequest::STATUS_ACCEPT);
            if (!$Request->save()) {
                Yii::app()->getAjax()->addErrors($Request);
                throw new \Exception();
            }

            $Moder = TransferFactory::factory('moder', $Request->getPriceType(), [$User, $Request->getBankid()]);
            if (!$Moder->accept($Request->getBalanceId()))
                throw new NException("Не удалось вывести екр", NException::ERROR_FINANCE_OUT);

            $t->commit();
            $User->sendBalanceToFront();

            Yii::app()->getAjax()
                ->addMessage('Вывод одобрен')
                ->runJS('updatePage', Yii::app()->createUrl('/admin/output/index'));
        } catch (\Exception $ex) {
            $t->rollback();
            \MException::logMongo($ex);
            Yii::app()->getAjax()->addErrors($ex->getMessage())->send();
        }

        Yii::app()->getAjax()->send();
    }
}