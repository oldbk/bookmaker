<?php
namespace frontend\modules\admin\controllers\actions\problem;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use Yii;
use SportEvent;

class IndexAction extends CAction
{
    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        $filter = Yii::app()->getRequest()->getParam('Filter', [
            'finish' => null,
        ]);

        $criteria = new \CDbCriteria();
        $criteria->with = ['sport'];
        $criteria->index = 'id';
        $criteria->order = '`t`.date_int asc';

        if ($filter['finish'] !== null) {
            $criteria->with = ['hasResolvedProblem'];
            $criteria->order = '`hasResolvedProblem`.update_at desc';
        } else
            $criteria->scopes = ['nTrash', 'haveProblem'];
        $criteria->with[] = 'betCount';
        $criteria->addCondition('(select count(id) from user_betting ub where ub.event_id = `t`.id) > 0');

        $pages = new \CPagination(SportEvent::model()->count($criteria));
        $pages->pageSize = 20;
        $pages->applyLimit($criteria);

        /** @var SportEvent[] $SportEventList */
        $SportEventList = SportEvent::model()->findAll($criteria);

        $user_id = [];
        $ProblemByEvent = [];
        $criteria = new \CDbCriteria();
        $criteria->addInCondition('event_id', array_keys($SportEventList));
        /** @var \SportEventProblem[] $ProblemList */
        $ProblemList = \SportEventProblem::model()->findAll($criteria);
        foreach ($ProblemList as $Problem) {
            $ProblemByEvent[$Problem->getEventId()][] = $Problem;
            if ($Problem->getResolverId() && !in_array($Problem->getResolverId(), $user_id))
                $user_id[] = $Problem->getResolverId();
        }

        $criteria = new \CDbCriteria();
        $criteria->addInCondition('id', $user_id);
        $criteria->index = 'id';
        $UserList = \User::model()->findAll($criteria);

        $params = [
            'SportEventList' => $SportEventList,
            'ProblemByEvent' => $ProblemByEvent,
            'UserList' => $UserList,
            'pages' => $pages,
            'filter' => $filter,
        ];
        if (Yii::app()->getRequest()->getIsAjaxRequest())
            Yii::app()->getAjax()
                ->addTrigger('page:loaded')
                ->addReplace('index', '#content-replacement', $params)
                ->send();
        else
            $this->getController()->render('index', $params);
    }
}