<?php
namespace frontend\modules\user\controllers\actions\finance;

use CAction;
use common\interfaces\iPrice;
use common\interfaces\iStatus;
use frontend\components\FrontendController;
use Yii;
use CDbCriteria;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 *
 * @method FrontendController getController()
 */
class IndexAction extends CAction
{
    public $admin_part = false;

    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        $criteria = new CDbCriteria();
        $criteria->addCondition('id = :id');
        $criteria->with = ['userBanks'];
        $criteria->params = [':id' => Yii::app()->getUser()->getId()];
        /** @var \User $User */
        $User = \User::model()->find($criteria);

        $criteria = new CDbCriteria();
        $criteria->addCondition('user_id = :user_id');
        $criteria->addCondition('status = :new');
        $criteria->params = [':user_id' => $User->getId(), ':new' => \UserOutputRequest::STATUS_NEW];
        $RequestList = \UserOutputRequest::model()->findAll($criteria);

        Yii::app()->getStatic()->setWww()->registerScriptFile('finance.js', \CClientScript::POS_END, !YII_DEBUG);

        $criteria = new CDbCriteria();
        $criteria->select = 'sum(`t`.price) as sum';
        $criteria->addCondition('`t`.user_id = :user_id');
        $criteria->addCondition('`t`.status = :new');
        $criteria->addCondition('`t`.price_type = :price_type');
        $criteria->group = '`t`.user_id';
        $criteria->params = [
            ':new' => iStatus::STATUS_NEW,
            ':user_id' => $User->getId(),
            ':price_type' => iPrice::TYPE_KR,
        ];
        /** @var \BettingGroup $krInProcess */
        $krInProcess = \BettingGroup::model()->find($criteria);

        $criteria = new CDbCriteria();
        $criteria->select = 'sum(`t`.price) as sum';
        $criteria->addCondition('`t`.user_id = :user_id');
        $criteria->addCondition('`t`.status = :new');
        $criteria->addCondition('`t`.price_type = :price_type');
        $criteria->group = '`t`.user_id';
        $criteria->params = [
            ':new' => iStatus::STATUS_NEW,
            ':user_id' => $User->getId(),
            ':price_type' => iPrice::TYPE_EKR,
        ];
        /** @var \BettingGroup $ekrInProcess */
        $ekrInProcess = \BettingGroup::model()->find($criteria);

		$criteria = new CDbCriteria();
		$criteria->select = 'sum(`t`.price) as sum';
		$criteria->addCondition('`t`.user_id = :user_id');
		$criteria->addCondition('`t`.status = :new');
		$criteria->addCondition('`t`.price_type = :price_type');
		$criteria->group = '`t`.user_id';
		$criteria->params = [
			':new' => iStatus::STATUS_NEW,
			':user_id' => $User->getId(),
			':price_type' => iPrice::TYPE_GOLD,
		];
		/** @var \BettingGroup $goldInProcess */
		$goldInProcess = \BettingGroup::model()->find($criteria);

        $criteria = new CDbCriteria();
        $criteria->select = 'sum(`t`.price) as sum';
        $criteria->addCondition('`t`.user_id = :user_id');
        $criteria->addCondition('`t`.status = :new');
        $criteria->addCondition('`t`.is_moder = 1');
        $criteria->addCondition('`t`.price_type = :price_type');
        $criteria->group = '`t`.user_id';
        $criteria->params = [
            ':new' => iStatus::STATUS_NEW,
            ':user_id' => $User->getId(),
            ':price_type' => iPrice::TYPE_KR,
        ];
        /** @var \UserBalance $outPutKr */
        $outPutKr = \UserBalance::model()->find($criteria);

        $criteria = new CDbCriteria();
        $criteria->select = 'sum(`t`.price) as sum';
        $criteria->addCondition('`t`.user_id = :user_id');
        $criteria->addCondition('`t`.status = :new');
        $criteria->addCondition('`t`.is_moder = 1');
        $criteria->addCondition('`t`.price_type = :price_type');
        $criteria->group = '`t`.user_id';
        $criteria->params = [
            ':new' => iStatus::STATUS_NEW,
            ':user_id' => $User->getId(),
            ':price_type' => iPrice::TYPE_EKR,
        ];
        /** @var \UserBalance $outPutEkr */
        $outPutEkr = \UserBalance::model()->find($criteria);

		$criteria = new CDbCriteria();
		$criteria->select = 'sum(`t`.price) as sum';
		$criteria->addCondition('`t`.user_id = :user_id');
		$criteria->addCondition('`t`.status = :new');
		$criteria->addCondition('`t`.is_moder = 1');
		$criteria->addCondition('`t`.price_type = :price_type');
		$criteria->group = '`t`.user_id';
		$criteria->params = [
			':new' => iStatus::STATUS_NEW,
			':user_id' => $User->getId(),
			':price_type' => iPrice::TYPE_GOLD,
		];
		/** @var \UserBalance $outPutGold */
		$outPutGold = \UserBalance::model()->find($criteria);

        $params = [
            'model' => $User,
            'RequestList' => $RequestList,
            'krInProcess' => $krInProcess ? $krInProcess->sum : '0.00',
            'ekrInProcess' => $ekrInProcess ? $ekrInProcess->sum : '0.00',
            'goldInProcess' => $goldInProcess ? $goldInProcess->sum : '0.00',
            'outPutKr' => $outPutKr ? $outPutKr->sum : '0.00',
            'outPutEkr' => $outPutEkr ? $outPutEkr->sum : '0.00',
            'outPutGold' => $outPutGold ? $outPutGold->sum : '0.00',
        ];

        $this->getController()->setPageTitle('Ввод\Вывод');
        if (Yii::app()->getRequest()->getIsAjaxRequest())
            Yii::app()->getAjax()
                ->addReplace('finance', '#content-replacement', $params)
                ->addTrigger('page:loaded')
                ->runJS('getLastOperation')
                ->send();
        else
            $this->getController()->render('finance', $params);
    }
}