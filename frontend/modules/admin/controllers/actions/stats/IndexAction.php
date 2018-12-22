<?php
namespace frontend\modules\admin\controllers\actions\stats;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use common\interfaces\iStatus;
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

        $criteria = new \CDbCriteria();
        $criteria->addCondition('status = :finish');
        $criteria->params = [':finish' => iStatus::STATUS_FINISH];
        //обработанные события
        $finish_event = \SportEvent::model()->count($criteria);

        $criteria = new \CDbCriteria();
        $criteria->addCondition('date_int > :time ');
        $criteria->addCondition('status != :finish ');
        $criteria->params = [
            ':time' => time(),
            ':finish' => iStatus::STATUS_FINISH
        ];
        //доступные события
        $new_event = \SportEvent::model()->count($criteria);

        $criteria = new \CDbCriteria();
        $criteria->addCondition('date_int < :time');
        $criteria->addCondition('status != :finish');
        $criteria->params = [
            ':time' => time(),
            ':finish' => iStatus::STATUS_FINISH,
        ];
        //доступные события
        $process_event = \SportEvent::model()->count($criteria);

        $criteria = new \CDbCriteria();
        $criteria->scopes = ['nTrash', 'haveProblem'];
        //событий с ошибками
        $error_event = \SportEvent::model()->count($criteria);

        $criteria = new \CDbCriteria();
        $criteria->addCondition('status = :finish');
        $criteria->addCondition('result_status != :new');
        $criteria->params = [
            ':finish' => iStatus::STATUS_FINISH,
            ':new' => iStatus::RESULT_NEW
        ];
        //обработано ставок
        $user_betting_finish = \UserBetting::model()->count($criteria);

        $criteria = new \CDbCriteria();
        $criteria->addCondition('status != :finish');
        $criteria->addCondition('result_status = :new');
        $criteria->params = [
            ':finish' => iStatus::STATUS_FINISH,
            ':new' => iStatus::RESULT_NEW
        ];
        //ставки еще не сыграли
        $user_betting_new = \UserBetting::model()->count($criteria);

        $criteria = new \CDbCriteria();
        $criteria->scopes = ['error'];
        //ставки с ошибками
        $user_betting_error = \UserBetting::model()->count($criteria);

        $criteria = new \CDbCriteria();
        $criteria->addCondition('`t`.status = :new');
        $criteria->with = ['eventOriginal' => ['select' => false, 'scopes' => ['have_result']]];
        $criteria->params = [
            ':new' => iStatus::STATUS_NEW,
        ];
        //новые ставки с результатом
        $user_betting_result = \UserBetting::model()->count($criteria);

        $criteria = new \CDbCriteria();
        $criteria->scopes = ['error'];
        //группы с ошибками
        $group_error = \BettingGroup::model()->count($criteria);

        $criteria = new \CDbCriteria();
        $criteria->addCondition('status = :have_result');
        $criteria->params = [
            ':have_result' => iStatus::STATUS_HAVE_RESULT,
        ];
        //группы в процессе
        $group_process = \BettingGroup::model()->count($criteria);

        $criteria = new \CDbCriteria();
        $criteria->addCondition('status = :new');
        $criteria->params = [
            ':new' => iStatus::STATUS_NEW,
        ];
        //группы еще не сыграли
        $group_new = \BettingGroup::model()->count($criteria);

        $criteria = new \CDbCriteria();
        $criteria->addCondition('bet_type = :ordinar');
        $criteria->params = [
            ':ordinar' => \BettingGroup::TYPE_ORDINAR,
        ];
        //группы еще не сыграли
        $group_ordinar = \BettingGroup::model()->count($criteria);

        $criteria = new \CDbCriteria();
        $criteria->addCondition('bet_type = :express');
        $criteria->params = [
            ':express' => \BettingGroup::TYPE_EXPRESS,
        ];
        //группы еще не сыграли
        $group_express = \BettingGroup::model()->count($criteria);

        $criteria = new \CDbCriteria();
        $criteria->addCondition('is_strange = 1');
        $strange_out = \UserBalance::model()->count($criteria);

        Yii::app()->getStatic()->setWww()->registerScriptFile('stats.js', \CClientScript::POS_END, !YII_DEBUG);

        $params = [
            'top' => $view,
            'type' => $type,
            //Стата
            'finish_event' => $finish_event,
            'new_event' => $new_event,
            'error_event' => $error_event,
            'process_event' => $process_event,

            'user_betting_finish' => $user_betting_finish,
            'user_betting_new' => $user_betting_new,
            'user_betting_error' => $user_betting_error,
            'user_betting_result' => $user_betting_result,

            'group_error' => $group_error,
            'group_process' => $group_process,
            'group_new' => $group_new,
            'group_ordinar' => $group_ordinar,
            'group_express' => $group_express,

            'strange_out' => $strange_out,

        ];

        if (Yii::app()->getRequest()->getIsAjaxRequest())
            Yii::app()->getAjax()
                ->addTrigger('page:loaded')
                ->addReplace('index', '#content-replacement', $params)
                ->runJS('buildChart', Yii::app()->createUrl('/admin/stats/charts'))
                ->send();
        else
            $this->getController()->render('index', $params);
    }
}