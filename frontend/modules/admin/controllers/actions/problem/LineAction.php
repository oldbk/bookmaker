<?php
namespace frontend\modules\admin\controllers\actions\problem;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use common\interfaces\iStatus;
use Yii;
use Sport;
use CDbCriteria;

class LineAction extends CAction
{
    public $admin_part = false;

    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        Yii::app()->getUser()->setState('return_link', Yii::app()->getRequest()->getUrl());

        $criteria = new \CDbCriteria();
        $criteria->index = 'id';
        $criteria->scopes = ['nTrash', 'haveProblem'];
        $criteria->with[] = 'betCount';
        $criteria->addCondition('(select count(id) from user_betting ub where ub.event_id = `t`.id) > 0');

        $pages = new \CPagination(\SportEvent::model()->count($criteria));
        $pages->pageSize = 15;
        $pages->applyLimit($criteria);

        $criteria->with = ['sport' => ['select' => false]];
        $criteria->order = 'sport.title asc';
        /** @var \SportEvent[] $EventList */
        $EventList = \SportEvent::model()->findAll($criteria);

        $criteria = new \CDbCriteria();
        $criteria->select = ['event_id', 'type', 'value'];
        $criteria->addInCondition('`t`.event_id', array_keys($EventList));
        $criteria->addCondition('`t`.position = :position');
        $criteria->params[':position'] = \SportEventRatio::POSITION_NEW;
        $new_ratio = \SportEventRatio::model()
            ->getCommandBuilder()
            ->createFindCommand(\SportEventRatio::model()->tableName(), $criteria)
            ->queryAll();
        $new_ratio_list = [];
        foreach ($new_ratio as $ratio)
            $new_ratio_list[$ratio['event_id']][$ratio['type']] = $ratio['value'];

        $criteria->params[':position'] = \SportEventRatio::POSITION_LAST;
        $last_ratio = \SportEventRatio::model()
            ->getCommandBuilder()
            ->createFindCommand(\SportEventRatio::model()->tableName(), $criteria)
            ->queryAll();
        $last_ratio_list = [];
        foreach ($last_ratio as $ratio)
            $last_ratio_list[$ratio['event_id']][$ratio['type']] = $ratio['value'];

        $List = [];
        $sport_ids = [];
        foreach ($EventList as $Event) {
            if (isset($new_ratio_list[$Event->getId()]))
                $Event->getNewRatio()->populateRecord($new_ratio_list[$Event->getId()]);
            if (isset($last_ratio_list[$Event->getId()]))
                $Event->getOldRatio()->populateRecord($last_ratio_list[$Event->getId()]);

            $List[$Event->getSportId()][$Event->getId()] = $Event;
            if (!in_array($Event->getSportId(), $sport_ids))
                $sport_ids[] = $Event->getSportId();
        }
        unset($EventList);

        $criteria = new \CDbCriteria();
        $criteria->order = '`t`.title asc';
        $criteria->addInCondition('id', $sport_ids);
        /** @var \Sport[] $models */
        $models = \Sport::model()->findAll($criteria);

        $params = [
            'models' => $models,
            'EventList' => $List,
            'pages' => $pages,
        ];
        Yii::app()->getStatic()->setLibrary('editable')
            ->registerCssFile('bootstrap-editable.css')
            ->registerScriptFile('bootstrap-editable.min.js');

        if (Yii::app()->getRequest()->getIsAjaxRequest())
            Yii::app()->getAjax()
                ->addTrigger('page:loaded')
                ->addReplace($pages->getCurrentPage() > 0 ? 'page/all' : 'all', '#content-replacement .line-block', $params)
                ->send();
        else
            $this->getController()->render('all', $params);
    }
}