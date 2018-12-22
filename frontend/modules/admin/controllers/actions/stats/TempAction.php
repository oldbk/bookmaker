<?php
namespace frontend\modules\admin\controllers\actions\stats;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use common\components\WebUser;
use CDbCriteria;
use UserBalance;

class TempAction extends CAction
{
    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        $out_kr = [];
        $in_kr = [];
        $out = 0;
        $in = 0;

        $criteria = new CDbCriteria();
        $criteria->addCondition('`t`.operation_type = :operation_type');
        $criteria->addCondition('`t`.price_type = :price_type');
        $criteria->addNotInCondition('`t`.user_id', WebUser::getAdminIds());
        $criteria->with = ['user'];

        $criteria->params = \CMap::mergeArray($criteria->params, [
            ':operation_type' => \UserBalanceOutput::OPERATION_TYPE_TAKE,
            ':price_type' => UserBalance::TYPE_KR,
        ]);
        /** @var UserBalance[] $OutputKr */
        $OutputKr = \UserBalanceOutput::model()->findAll($criteria);
        foreach ($OutputKr as $Item) {
            if (!isset($out_kr[$Item->user->getLogin()]))
                $out_kr[$Item->user->getLogin()] = 0;

            $out_kr[$Item->user->getLogin()] += $Item->getPrice();
            $out += $Item->getPrice();
        }

        $criteria->params = \CMap::mergeArray($criteria->params, [
            ':operation_type' => \UserBalanceInput::OPERATION_TYPE_ADD,
            ':price_type' => UserBalance::TYPE_KR
        ]);
        /** @var \UserBalance[] $inputKr */
        $inputKr = \UserBalanceInput::model()->findAll($criteria);
        foreach ($inputKr as $Item) {
            if (!isset($out_kr[$Item->user->getLogin()]))
                $in_kr[$Item->user->getLogin()] = 0;

            $in_kr[$Item->user->getLogin()] += $Item->getPrice();
            $in += $Item->getPrice();
        }

        $this->controller->render('temp', ['out_kr' => $out_kr, 'in_kr' => $in_kr, 'out' => $out, 'in' => $in]);
    }
}