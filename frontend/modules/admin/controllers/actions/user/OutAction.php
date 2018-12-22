<?php
namespace frontend\modules\admin\controllers\actions\user;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use common\interfaces\iStatus;
use Yii;
use User;
use CPagination;
use CDbCriteria;

class OutAction extends CAction
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

        $post = Yii::app()->getRequest()->getParam('UserOut', ['start' => null, 'end' => null]);

        $criteria = new CDbCriteria();
        $criteria->addCondition('user_id = :id');
        $criteria->addCondition('operation_type = :add');
        $criteria->addCondition('`t`.status = :finish or `t`.status = :new');
        if (!empty($post['start']) && !empty($post['end']))
            $criteria->addBetweenCondition('create_at', strtotime($post['start'] . ' 00:00:00'), strtotime($post['end'] . ' 23:59:59'));
        $criteria->order = 'create_at desc';
        $criteria->params = \CMap::mergeArray($criteria->params, [
            ':id' => $User->getId(),
            ':add' => \UserBalanceOutput::OPERATION_TYPE_TAKE,
            ':finish' => iStatus::STATUS_FINISH,
            ':new' => iStatus::STATUS_NEW
        ]);

        $pages = new \CPagination(\UserBalanceOutput::model()->count($criteria));
        $pages->pageSize = 20;
        $pages->applyLimit($criteria);
        $pages->route = '/admin/user/out';

        $InputOutput = \UserBalanceOutput::model()->findAll($criteria);

        $criteria = new CDbCriteria();
        $criteria->select = 'sum(`t`.price) as sum';
        $criteria->addCondition('`t`.operation_type = :operation_type');
        $criteria->addCondition('`t`.price_type = :price_type');
        $criteria->addCondition('`t`.user_id = :user_id');
        $criteria->addCondition('`t`.status = :finish or `t`.status = :new');
        $criteria->params = \CMap::mergeArray($criteria->params, [
            ':operation_type' => \UserBalanceOutput::OPERATION_TYPE_TAKE,
            ':price_type' => \UserBalance::TYPE_KR,
            ':user_id' => $User->getId(),
            ':finish' => iStatus::STATUS_FINISH,
            ':new' => iStatus::STATUS_NEW
        ]);

        /** @var \UserBalanceInput $OutputKR */
        $OutputKR = \UserBalanceOutput::model()->find($criteria);

        $criteria->params[':price_type'] = \UserBalance::TYPE_EKR;
        /** @var \UserBalanceInput $OutputEKR */
        $OutputEKR = \UserBalanceOutput::model()->find($criteria);

        $params = [
            'models' => $InputOutput,
            'pages' => $pages,
            'user' => $User,
            'post' => $post,
            'link' => Yii::app()->createUrl('/admin/user/out', ['user_id' => $User->getId()]),
            'OutputKR' => $OutputKR->sum,
            'OutputEKR' => $OutputEKR->sum,
        ];

        Yii::app()->getAjax()->addReplace('_out', '#content-replacement #replace-info-block', $params)->send();
    }
}