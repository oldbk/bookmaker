<?php
namespace frontend\modules\admin\controllers\actions\user;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use Yii;
use User;
use CPagination;
use CDbCriteria;

class InAction extends CAction
{
    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        $userId = Yii::app()->getRequest()->getParam('user_id');
        $criteria = new CDbCriteria();
        $criteria->addCondition('id = :id');
        $criteria->params = [':id' => $userId];
        /** @var User $User */
        $User = User::model()->find($criteria);

        $post = Yii::app()->getRequest()->getParam('UserIn', ['start' => null, 'end' => null]);

        $criteria = new CDbCriteria();
        $criteria->addCondition('`t`.user_id = :id');
        $criteria->addCondition('`t`.operation_type = :add or `t`.operation_type = :return or `t`.operation_type = :akcii');
        $criteria->with = ['akcii'];
        if (!empty($post['start']) && !empty($post['end']))
            $criteria->addBetweenCondition('`t`.create_at', strtotime($post['start'] . ' 00:00:00'), strtotime($post['end'] . ' 23:59:59'));
        $criteria->order = '`t`.create_at desc';
        $criteria->params = \CMap::mergeArray($criteria->params, [
            ':id' => $User->getId(),
            ':add' => \UserBalanceInput::OPERATION_TYPE_ADD,
            ':return' => \UserBalanceOutput::OPERATION_TYPE_RETURN,
            ':akcii' => \UserBalanceOutput::OPERATION_TYPE_AKCII,
        ]);

        $pages = new \CPagination(\UserBalanceInput::model()->count($criteria));
        $pages->pageSize = 10;
        $pages->applyLimit($criteria);
        $pages->route = '/admin/user/in';

        $InputOutput = \UserBalanceInput::model()->findAll($criteria);

        $criteria = new CDbCriteria();
        $criteria->select = 'sum(`t`.price) as sum';
        $criteria->addCondition('`t`.operation_type = :operation_type');
        $criteria->addCondition('`t`.price_type = :price_type');
        $criteria->addCondition('`t`.user_id = :user_id');
        $criteria->params = \CMap::mergeArray($criteria->params, [
            ':operation_type' => \UserBalanceInput::OPERATION_TYPE_ADD,
            ':price_type' => \UserBalance::TYPE_KR,
            ':user_id' => $User->getId(),
        ]);

        /** @var \UserBalanceInput $InputKR */
        $InputKR = \UserBalanceInput::model()->find($criteria);

        $criteria->params[':price_type'] = \UserBalance::TYPE_EKR;
        /** @var \UserBalanceInput $InputEKR */
        $InputEKR = \UserBalanceInput::model()->find($criteria);

        $params = [
            'models' => $InputOutput,
            'pages' => $pages,
            'user' => $User,
            'post' => $post,
            'link' => Yii::app()->createUrl('/admin/user/in', ['user_id' => $User->getId()]),
            'InputKR' => $InputKR->sum,
            'InputEKR' => $InputEKR->sum,
        ];

        Yii::app()->getAjax()->addReplace('_in', '#content-replacement #replace-info-block', $params)->send();
    }
}