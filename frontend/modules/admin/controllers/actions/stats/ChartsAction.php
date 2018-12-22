<?php
namespace frontend\modules\admin\controllers\actions\stats;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use common\components\WebUser;
use common\interfaces\iStatus;
use Yii;
use CDbCriteria;
use UserBalance;

class ChartsAction extends CAction
{
    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        $data = [
            'voucher_in' => [],
            'voucher_out' => [],
            'ekr_in' => [],
            'ekr_out' => [],
            'kr_in' => [],
            'kr_out' => [],
			'gold_in' => [],
			'gold_out' => [],
        ];

        $criteria = new CDbCriteria();
        $criteria->select = 'sum(`t`.price) as sum';
        $criteria->addCondition('`t`.operation_type = :operation_type');
        $criteria->addCondition('`t`.price_type = :price_type');
        $criteria->addCondition('`t`.status = :finish or `t`.status =:new');
        $criteria->addNotInCondition('`t`.user_id', WebUser::getAdminIds());
        $criteria->params = \CMap::mergeArray($criteria->params, [
            ':operation_type' => \UserBalanceOutput::OPERATION_TYPE_TAKE,
            ':price_type' => UserBalance::TYPE_KR,
            ':finish' => iStatus::STATUS_FINISH,
            ':new' => iStatus::STATUS_NEW,
        ]);
        /** @var UserBalance $OutputKr */
        $OutputKr = \UserBalanceOutput::model()->find($criteria);

        $criteria->params = \CMap::mergeArray($criteria->params, [
            ':operation_type' => \UserBalanceOutput::OPERATION_TYPE_TAKE,
            ':price_type' => UserBalance::TYPE_EKR
        ]);
        /** @var \UserBalance $OutputEkr */
        $OutputEkr = \UserBalanceOutput::model()->find($criteria);

		$criteria->params = \CMap::mergeArray($criteria->params, [
			':operation_type' => \UserBalanceOutput::OPERATION_TYPE_TAKE,
			':price_type' => UserBalance::TYPE_GOLD
		]);
		/** @var \UserBalance $OutputGold */
		$OutputGold = \UserBalanceOutput::model()->find($criteria);

        $criteria->params = \CMap::mergeArray($criteria->params, [
            ':operation_type' => \UserBalanceInput::OPERATION_TYPE_ADD,
            ':price_type' => UserBalance::TYPE_KR
        ]);
        /** @var \UserBalance $inputKr */
        $inputKr = \UserBalanceInput::model()->find($criteria);

        $criteria->params = \CMap::mergeArray($criteria->params, [
            ':operation_type' => \UserBalanceInput::OPERATION_TYPE_ADD,
            ':price_type' => UserBalance::TYPE_EKR
        ]);
        /** @var \UserBalance $inputEkr */
        $inputEkr = \UserBalanceInput::model()->find($criteria);

		$criteria->params = \CMap::mergeArray($criteria->params, [
			':operation_type' => \UserBalanceInput::OPERATION_TYPE_ADD,
			':price_type' => UserBalance::TYPE_GOLD
		]);
		/** @var \UserBalance $inputGold */
		$inputGold = \UserBalanceInput::model()->find($criteria);

        $criteria = new CDbCriteria();
        $criteria->select = 'sum(`t`.kr_balance) as sum_kr, sum(`t`.ekr_balance) as sum_ekr';
        $criteria->addNotInCondition('`t`.id', WebUser::getAdminIds());
        /** @var \User $balance */
        $balance = \User::model()->find($criteria);

        $criteria = new CDbCriteria();
        $criteria->select = 'sum(`t`.price) as sum';
        $criteria->addNotInCondition('`t`.user_id', WebUser::getAdminIds());
        $criteria->addCondition('`t`.status = :new');
        $criteria->addCondition('`t`.price_type = :price_type');
        $criteria->with = ['notLossEvent'];
        $criteria->params = \CMap::mergeArray($criteria->params, [
            ':price_type' => UserBalance::TYPE_KR,
            ':new' => iStatus::STATUS_NEW,
        ]);
        /** @var \BettingGroup $betKr */
        $betKr = \BettingGroup::model()->find($criteria);

        $criteria = new CDbCriteria();
        $criteria->select = 'sum(`t`.price) as sum';
        $criteria->addNotInCondition('`t`.id', WebUser::getAdminIds());
        $criteria->addCondition('`t`.status = :new');
        $criteria->addCondition('`t`.price_type = :price_type');
        $criteria->with = ['notLossEvent'];
        $criteria->params = \CMap::mergeArray($criteria->params, [
            ':price_type' => UserBalance::TYPE_EKR,
            ':new' => iStatus::STATUS_NEW,
        ]);
        /** @var \BettingGroup $betEkr */
        $betEkr = \BettingGroup::model()->find($criteria);

		$criteria = new CDbCriteria();
		$criteria->select = 'sum(`t`.price) as sum';
		$criteria->addNotInCondition('`t`.id', WebUser::getAdminIds());
		$criteria->addCondition('`t`.status = :new');
		$criteria->addCondition('`t`.price_type = :price_type');
		$criteria->with = ['notLossEvent'];
		$criteria->params = \CMap::mergeArray($criteria->params, [
			':price_type' => UserBalance::TYPE_GOLD,
			':new' => iStatus::STATUS_NEW,
		]);
		/** @var \BettingGroup $betGold */
		$betGold = \BettingGroup::model()->find($criteria);

        $criteria = new CDbCriteria();
        $criteria->select = 'sum(`t`.price) as sum';
        $criteria->addNotInCondition('`t`.id', WebUser::getAdminIds());
        $criteria->addCondition('`t`.status = :finish');
        $criteria->addCondition('`t`.price_type = :price_type');
        $criteria->addCondition('`t`.result_status = :result_status');
        $criteria->params = \CMap::mergeArray($criteria->params, [
            ':price_type' => UserBalance::TYPE_KR,
            ':finish' => iStatus::STATUS_FINISH,
            ':result_status' => iStatus::RESULT_LOSS,
        ]);
        /** @var \BettingGroup $lossKr */
        //$lossKr = \BettingGroup::model()->find($criteria);

        $criteria = new CDbCriteria();
        $criteria->select = 'sum(`t`.price) as sum';
        $criteria->addNotInCondition('`t`.id', WebUser::getAdminIds());
        $criteria->addCondition('`t`.status = :finish');
        $criteria->addCondition('`t`.price_type = :price_type');
        $criteria->addCondition('`t`.result_status = :result_status');
        $criteria->params = \CMap::mergeArray($criteria->params, [
            ':price_type' => UserBalance::TYPE_EKR,
            ':finish' => iStatus::STATUS_FINISH,
            ':result_status' => iStatus::RESULT_LOSS,
        ]);
        /** @var \BettingGroup $lossEkr */
        //$lossEkr = \BettingGroup::model()->find($criteria);

        /** @var \Stats[] $models */
        $models = \Stats::model()->findAll();
        foreach ($models as $model) {
            $data['ekr_diff'][] = [($model->getStatsAt() + 3600 * 3) * 1000.0, (float)($model->getMoneyEkrIn() - $model->getMoneyEkrOut())];
            $data['kr_diff'][] = [($model->getStatsAt() + 3600 * 3) * 1000.0, (float)($model->getMoneyKrIn() - $model->getMoneyKrOut())];
            $data['gold_diff'][] = [($model->getStatsAt() + 3600 * 3) * 1000.0, (float)($model->getMoneyGoldIn() - $model->getMoneyGoldOut())];
        }

        $krOut = $OutputKr->sum ? $OutputKr->sum : '0.00';
        $krIn = $inputKr->sum ? $inputKr->sum : '0.00';
        $ekrOut = $OutputEkr->sum ? $OutputEkr->sum : '0.00';
        $ekrIn = $inputEkr->sum ? $inputEkr->sum : '0.00';
		$goldOut = $OutputGold->sum ? $OutputGold->sum : '0.00';
		$goldIn = $inputGold->sum ? $inputGold->sum : '0.00';
        $balanceKr = $balance ? $balance->sum_kr : '0.00';
        $balanceEkr = $balance ? $balance->sum_ekr : '0.00';
        $balanceGold = $balance ? $balance->sum_gold : '0.00';
        $betKr = $betKr ? $betKr->sum : '0.00';
        $betEkr = $betEkr ? $betEkr->sum : '0.00';
        $betGold = $betGold ? $betGold->sum : '0.00';

        $string = '';
        //$string .= sprintf('Ввели суммы: %s кр/%s екр', $krIn, $ekrIn);
        //$string .= "<br>---------------------------------------<br>";
        //$string .= sprintf('Проиграно ставок на сумму: %s кр/%s екр', $lossKr->sum, $lossEkr->sum);
        //$string .= "<br>---------------------------------------<br>";
        $string .= sprintf('Доходность: %s кр/%s екр/%s мон', $krIn - $krOut, $ekrIn - $ekrOut, $goldIn - $goldOut);
        $string .= "<br>---------------------------------------<br>";
        $string .= sprintf('Балансы: %s кр/%s екр/%s мон', $balanceKr, $balanceEkr, $balanceGold);
        $string .= "<br>---------------------------------------<br>";
        $string .= sprintf('В ставках: %s кр/%s екр/%s мон', $betKr, $betEkr, $betGold);
        $string .= "<br>---------------------------------------<br>";
        $string .= sprintf('Чистая прибыль: %s кр/%s екр/%s мон', $krIn - $krOut - $balanceKr - $betKr, $ekrIn - $ekrOut - $balanceEkr - $betEkr, $goldIn - $goldOut - $balanceGold - $betGold);

        Yii::app()->getAjax()->addOther(['data' => $data, 'title' => $string])->send();
    }
}