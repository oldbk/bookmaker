<?php
/**
 * Created by PhpStorm.
 */
namespace common\components;

use common\interfaces\iPrice;
use \common\interfaces\iStatus;
use \common\helpers\Convert;
use \UserBalance;
use \CDbCriteria;
use \BettingGroup;
class CheckValidOutput extends \CApplicationComponent
{
    public function check($user_id, $price_type)
    {
        /** @var \User $User */
        $User = \User::model()->findByPk($user_id);
        if(!$User)
            return false;

        try {
            $criteria = new CDbCriteria();
            $criteria->select = 'sum(`t`.price) as sum';
            $criteria->addCondition('`t`.operation_type = :operation_type');
            $criteria->addCondition('`t`.price_type = :price_type');
            $criteria->addCondition('`t`.user_id = :user_id');
            $criteria->addCondition('`t`.status = :finish or `t`.status = :new');
            $criteria->addNotInCondition('`t`.user_id', WebUser::getAdminIds());
            $criteria->group = 't.user_id';
            $criteria->params = \CMap::mergeArray($criteria->params, [
                ':operation_type' => \UserBalanceOutput::OPERATION_TYPE_TAKE,
                ':price_type' => $price_type,
                ':user_id' => $user_id,
                ':finish' => iStatus::STATUS_FINISH,
                ':new' => iStatus::STATUS_NEW,
            ]);
            /** @var UserBalance $Output */
            $Output = \UserBalanceOutput::model()->find($criteria);
            $output_sum = $Output ? $Output->sum : 0.00;

            //суммарный ввод
            $criteria = new CDbCriteria();
            $criteria->select = 'sum(`t`.price) as sum';
            $criteria->addCondition('`t`.operation_type = :operation_type');
            $criteria->addCondition('`t`.price_type = :price_type');
            $criteria->addCondition('`t`.user_id = :user_id');
            $criteria->addCondition('`t`.status = :finish');
            $criteria->group = 't.user_id';
            $criteria->params = \CMap::mergeArray($criteria->params, [
                ':operation_type' => \UserBalanceOutput::OPERATION_TYPE_ADD,
                ':user_id' => $user_id,
                ':price_type' => $price_type,
                ':finish' => iStatus::STATUS_FINISH,
            ]);
            /** @var UserBalance $Input */
            $Input = \UserBalanceInput::model()->find($criteria);
            $input_sum = $Input ? $Input->sum : 0.00;

            $criteria = new CDbCriteria();
            $criteria->addCondition('`t`.user_id = :user_id');
            $criteria->addCondition('`t`.price_type = :price_type');
            $criteria->params = [
                ':user_id' => $user_id,
                ':price_type' => $price_type
            ];
            /** @var BettingGroup[] $Betting */
            $Betting = BettingGroup::model()->findAll($criteria);

            $input = ($input_sum * 100 - $output_sum * 100 - $this->getBalanceByType($User, $price_type) * 100) / 100;
            $win = 0;
            $loss = 0;
            foreach ($Betting as $Group) {
                switch ($Group->getResultStatus()) {
                    case iStatus::RESULT_WIN:
                        $win = ($win * 100 + $Group->getPaymentSum() * 100 - $Group->getPrice() * 100) / 100;
                        break;
                    case iStatus::RESULT_LOSS:
                    case iStatus::RESULT_NEW:
                        $loss = ($loss * 100 - $Group->getPrice() * 100) / 100;
                        break;
                }
            }

            $input = ($input * 100 + $win * 100 - $loss * 100)/100;
            return Convert::getMoneyFormat($input) >= 0;
        } catch (\Exception $ex) {
            \MException::logMongo($ex);
            return false;
        }
    }

    /**
     * @param \User $User
     * @param $type
     * @return mixed
     */
    private function getBalanceByType($User, $type)
    {
        switch ($type) {
            case iPrice::TYPE_EKR:
                return $User->getEkrBalance();
                break;
            case iPrice::TYPE_KR:
                return $User->getKrBalance();
                break;
        }

        return 0;
    }
}