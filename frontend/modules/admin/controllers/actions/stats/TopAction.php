<?php
namespace frontend\modules\admin\controllers\actions\stats;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use Yii;

class TopAction extends CAction
{
    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        $view = [
            'user_in' => [],
            'user_out' => [],
            'user_balance' => [],
        ];
        $type = Yii::app()->getRequest()->getParam('type', \User::TYPE_KR);

        $criteria = new \CDbCriteria();
        $criteria->addCondition('price_type = :price_type');
        $criteria->addNotInCondition('user_id', Yii::app()->getUser()->getAdminIds());
        $criteria->params = \CMap::mergeArray($criteria->params, [':price_type' => $type]);
        $criteria->with = ['user'];
        $criteria->limit = 20;
        $criteria->order = 'sum_in desc';
        /** @var \UserActiveBalance[] $UserIn */
        $UserIn = \UserActiveBalance::model()->findAll($criteria);
        foreach ($UserIn as $User)
            $view['user_in'][] = [
                'login' => $User->user->buildLogin(),
                'price' => $User->getSumIn()
            ];

        $criteria->order = 'sum_out desc';
        /** @var \UserActiveBalance[] $UserOut */
        $UserOut = \UserActiveBalance::model()->findAll($criteria);
        foreach ($UserOut as $User)
            $view['user_out'][] = [
                'login' => $User->user->buildLogin(),
                'price' => $User->getSumOut()
            ];

        $field = null;
        switch ($type) {
            /*case \User::TYPE_VOUCHER:
                $field = 'voucher_balance';
                break;*/
            case \User::TYPE_EKR:
                $field = 'ekr_balance';
                break;
            default:
                $field = 'kr_balance';
                break;
        }
        $criteria = new \CDbCriteria();
        $criteria->addNotInCondition('id', Yii::app()->getUser()->getAdminIds());
        $criteria->limit = 20;
        $criteria->order = $field . ' desc';
        /** @var \User[] $UserBalance */
        $UserBalance = \User::model()->findAll($criteria);
        foreach ($UserBalance as $User)
            $view['user_balance'][] = [
                'login' => $User->buildLogin(),
                'price' => $User->{$field}
            ];

        $params = [
            'top' => $view,
            'type' => $type
        ];

        Yii::app()->getAjax()
            ->addTrigger('page:loaded')
            ->addReplace('_top', '#content-replacement #replace', $params)
            ->send();
    }
}