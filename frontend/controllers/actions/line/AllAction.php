<?php
namespace frontend\controllers\actions\line;

use CAction;
use frontend\components\FrontendController;
use Yii;
use CDbCriteria;
use SportEvent;

/**
 * Class AllAction
 * @package frontend\controllers\actions\line
 * @method FrontendController getController()
 */
class AllAction extends CAction
{
    public $admin_part = false;

    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        $filter = Yii::app()->getRequest()->getParam('Filter', [
            'start' => date('d.m.Y'),
            'end' => date('d.m.Y'),
        ]);

        $criteria = new \CDbCriteria();
        $criteria->scopes = ['for_user'];
        $criteria->index = 'id';
        $criteria->order = 'sport.title asc, t.date_int asc';
        $criteria->with = ['sport' => ['select' => 'title']];
        if (!empty($filter['start'])) {
            $criteria->addCondition('`t`.date_int >= :start');
            $criteria->params[':start'] = strtotime($filter['start'] . ' 00:00:00');
        }
        if (!empty($filter['end'])) {
            $criteria->addCondition('`t`.date_int <= :end');
            $criteria->params[':end'] = strtotime($filter['end'] . ' 23:59:59');
        }

        $pages = new \CPagination(SportEvent::model()->count($criteria));
        $pages->pageSize = 15;
        $pages->applyLimit($criteria);

        /** @var SportEvent[] $EventList */
        $EventList = SportEvent::model()->findAll($criteria);

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
                $Event->getNewRatio()->populateRecord($new_ratio_list[$Event->getId()], true);
            if (isset($last_ratio_list[$Event->getId()]))
                $Event->getOldRatio()->populateRecord($last_ratio_list[$Event->getId()], true);

            $List[$Event->getSportId()][$Event->getId()] = $Event;
            if (!in_array($Event->getSportId(), $sport_ids))
                $sport_ids[] = $Event->getSportId();
        }
        unset($EventList);

        $criteria = new CDbCriteria();
        $criteria->order = '`t`.title asc';
        $criteria->addInCondition('id', $sport_ids);
        /** @var \Sport[] $models */
        $models = \Sport::model()->findAll($criteria);

        $params = [
            'sports' => $models,
            'EventList' => $List,
            'filter' => $filter,
            'pages' => $pages,
        ];

        $this->getController()->setPageTitle('Вся линия');
        if (Yii::app()->getRequest()->getIsAjaxRequest()) {
            Yii::app()->getAjax()
                ->addTrigger('page:loaded')
                ->addReplace($pages->getCurrentPage() > 0 ? 'page/all' : 'all', '#content-replacement', $params)
                ->send();
        } else
            $this->getController()->render('all', $params);
    }
}