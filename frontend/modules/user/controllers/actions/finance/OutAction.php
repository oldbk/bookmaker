<?php
namespace frontend\modules\user\controllers\actions\finance;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use Yii;
use CDbCriteria;

class OutAction extends CAction
{
    public $admin_part = false;

    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        $post = Yii::app()->getRequest()->getParam('Out', ['start' => null, 'end' => null]);

        $criteria = new CDbCriteria();
        $criteria->addCondition('user_id = :user_id');
        $criteria->addInCondition('payment_type', [\UserBalance::BALANCE_TYPE_OUTPUT, \UserBalance::BALANCE_TYPE_BET]);
        if (!empty($post['start']) && !empty($post['end']))
            $criteria->addBetweenCondition('create_at', strtotime($post['start'] . ' 00:00:00'), strtotime($post['end'] . ' 23:59:59'));
        $criteria->order = 'create_at desc';
        $criteria->params = \CMap::mergeArray($criteria->params, [
            ':user_id' => Yii::app()->getUser()->getId(),
        ]);

        $dependency = new \CDbCacheDependency('SELECT MAX(update_at) FROM {{user_balance}} where user_id = :user_id and (payment_type = :p_output or payment_type = :p_bet)');
        $dependency->params = [
            ':user_id' => Yii::app()->getUser()->getId(),
            ':p_output' => \UserBalance::BALANCE_TYPE_OUTPUT,
            ':p_bet' => \UserBalance::BALANCE_TYPE_BET,
        ];
        $dependency->reuseDependentData = true;

        $pages = new \CPagination(\UserBalance::model()->cache(3600, $dependency)->count($criteria));
        $pages->pageSize = 20;
        $pages->applyLimit($criteria);

        $InputOutput = \UserBalance::model()->cache(3600, $dependency)->findAll($criteria);

        $params = [
            'models' => $InputOutput,
            'pages' => $pages
        ];

        Yii::app()->getAjax()->addReplace('_out', '#content-replacement #out tbody', $params)->send();
    }
}