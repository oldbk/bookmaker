<?php
namespace frontend\modules\admin\controllers\actions\output;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use common\interfaces\iStatus;
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
        $filter_placeholder = [
            'user' => -1,
            'price_type' => -1
        ];
        $filter = \CMap::mergeArray($filter_placeholder, Yii::app()->getRequest()->getParam('Filter', []));

        $criteria = new CDbCriteria();
        $criteria->addCondition('`t`.operation_type = :take');
        $criteria->addCondition('`t`.status = :finish');
        $criteria->order = '`t`.create_at desc';
        $criteria->with = ['user'];
        $criteria->params = \CMap::mergeArray($criteria->params, [
            ':take' => \UserBalanceOutput::OPERATION_TYPE_TAKE,
            ':finish' => iStatus::STATUS_FINISH
        ]);
        if ($filter['user'] > 0) {
            $criteria->addCondition('`t`.user_id = :user_id');
            $criteria->params[':user_id'] = $filter['user'];
        }

        if ($filter['price_type'] >= 0) {
            $criteria->addCondition('`t`.price_type = :price_type');
            $criteria->params[':price_type'] = $filter['price_type'];
        }

        $pages = new \CPagination(\UserBalanceOutput::model()->count($criteria));
        $pages->pageSize = 20;
        $pages->applyLimit($criteria);

        $InputOutput = \UserBalanceOutput::model()->findAll($criteria);

        $params = [
            'models' => $InputOutput,
            'pages' => $pages,
            'filter' => $filter
        ];

        if (Yii::app()->getRequest()->getIsAjaxRequest())
            Yii::app()->getAjax()
                ->addTrigger('page:loaded')
                ->addReplace('outlist', '#content-replacement', $params)
                ->send();
        else {
            $this->getController()->render('outlist', $params);
        }
    }
}