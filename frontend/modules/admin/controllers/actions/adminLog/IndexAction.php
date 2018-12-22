<?php
namespace frontend\modules\admin\controllers\actions\adminLog;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use Yii;
use CDbCriteria;
use CActiveDataProvider;

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
        $placeholder = [
            'user' => -1,
            'start' => null,
            'end' => null,
            'user_login' => null
        ];
        $filter = Yii::app()->getRequest()->getParam('Filter', []);
        $filter = \CMap::mergeArray($placeholder, $filter);

        $AdminLog = new \AdminHistory('search');
        $AdminLog->unsetAttributes();
        $post = Yii::app()->getRequest()->getParam('AdminHistory');
        if ($post)
            $AdminLog->setAttributes($post);

        $criteria = new CDbCriteria();
        $criteria->with = ['user', 'event'];
        if($filter['user'] > 0) {
            $criteria->addCondition('`t`.admin_id = :user_id');
            $criteria->params[':user_id'] = $filter['user'];
        }
        if(!empty($filter['start'])) {
            $criteria->addCondition('`t`.create_at >= :start');
            $criteria->params[':start'] = strtotime($filter['start'].' 00:00:00');
        }
        if(!empty($filter['end'])) {
            $criteria->addCondition('`t`.create_at <= :end');
            $criteria->params[':end'] = strtotime($filter['end'].' 23:59:59');
        }

        $dataProvider = new CActiveDataProvider($AdminLog, [
            'criteria' => $criteria,
            'pagination' => ['pageSize' => 20],
            'sort' => [
                'defaultOrder' => '`t`.id desc'
            ]
        ]);

        $params = [
            'dataProvider' => $dataProvider,
            'filter' => $filter
        ];

        if (Yii::app()->getRequest()->getIsAjaxRequest())
            Yii::app()->getAjax()
                ->addTrigger('page:loaded')
                ->addReplace('list', '#content-replacement', $params)
                ->send();
        else
            $this->getController()->render('list', $params);
    }
}