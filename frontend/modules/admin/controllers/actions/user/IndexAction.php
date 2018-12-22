<?php
namespace frontend\modules\admin\controllers\actions\user;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use Yii;

class IndexAction extends CAction
{
    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        $placeholder = [
            'user' => null,
            'user_login' => null,
            'is_blocked' => -1
        ];
        $filter = \CMap::mergeArray($placeholder, Yii::app()->getRequest()->getParam('Filter', []));

        $criteria = new \CDbCriteria();
        $criteria->with = [
            'userActiveBalanceKR' => ['together' => true],
            'userActiveBalanceEKR' => ['together' => true],
        ];
        $criteria->addCondition('userActiveBalanceKR.user_id is not null or userActiveBalanceEKR.user_id is not null');
        if ($filter['user'] > 0) {
            $criteria->addCondition('id = :id');
            $criteria->params[':id'] = $filter['user'];
        }
        if ($filter['is_blocked'] > -1) {
            $criteria->addCondition('is_blocked = :is_blocked');
            $criteria->params[':is_blocked'] = $filter['is_blocked'];
        }

        $dataProvider = new \CActiveDataProvider('User', [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => 30
            ],
            'sort' => [
                'attributes' => [
                    'userActiveBalanceKR.active_diff' => [
                        'asc' => 'userActiveBalanceKR.active_diff',
                        'desc' => 'userActiveBalanceKR.active_diff DESC',
                    ],
                    'userActiveBalanceEKR.active_diff' => [
                        'asc' => 'userActiveBalanceEKR.active_diff',
                        'desc' => 'userActiveBalanceEKR.active_diff DESC',
                    ],
                    '*',
                ],
            ],
        ]);

        $params = [
            'dataProvider' => $dataProvider,
            'filter' => $filter
        ];

        Yii::app()->getStatic()->setWww()->registerScriptFile('user.js', \CClientScript::POS_END, !YII_DEBUG);
        if (Yii::app()->getRequest()->getIsAjaxRequest())
            Yii::app()->getAjax()
                ->addTrigger('page:loaded')
                ->addReplace('index', '#content-replacement', $params)
                ->send();
        else
            $this->getController()->render('index', $params);
    }
}