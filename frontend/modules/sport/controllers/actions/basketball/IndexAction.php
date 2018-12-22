<?php
namespace frontend\modules\sport\controllers\actions\basketball;

use CAction;
use common\components\DateTimeFormat;
use common\helpers\SportHelper;
use frontend\components\FrontendController;
use Yii;
use CDbCriteria;

/**
 * Class IndexAction
 * @package frontend\controllers\actions\site
 *
 * @method FrontendController getController()
 */
class IndexAction extends CAction
{
    public function run()
    {
        $date = Yii::app()->getRequest()->getParam('date');
        $sport = Yii::app()->getRequest()->getParam('sport');

        $criteria = new \CDbCriteria();
        $criteria->scopes = ['for_user'];
        $criteria->index = 'id';
        $criteria->order = '`t`.date_int asc';

        $range = DateTimeFormat::getTimeRangeByWord($date);
        if($range) {
            $criteria->addCondition('`t`.date_int > :start');
            $criteria->addCondition('`t`.date_int < :end');
            $criteria->params[':start'] = $range['start'];
            $criteria->params[':end'] = $range['end'];
        }

        if($sport && is_numeric($sport)) {
            $criteria->addCondition('`t`.sport_id = :sport_id');
            $criteria->params[':sport_id'] = $sport;
        }

        $pages = new \CPagination(\BasketballEvent::model()->count($criteria));
        $pages->pageSize = 15;
        $pages->applyLimit($criteria);

        /** @var \iSportEvent[] $Events */
        $Events = \BasketballEvent::model()->findAll($criteria);

        $sport_ids = [];
        $new_ratio = \SportEventRatio::getByIds(array_keys($Events), \SportEventRatio::POSITION_NEW);
        $last_ratio = \SportEventRatio::getByIds(array_keys($Events), \SportEventRatio::POSITION_LAST);
        $List = [];
        foreach ($Events as $Event) {
            if(isset($new_ratio[$Event->getId()]))
                $Event->getNewRatio()->populateRecord($new_ratio[$Event->getId()], true);
            if(isset($last_ratio[$Event->getId()]))
                $Event->getOldRatio()->populateRecord($last_ratio[$Event->getId()], true);

            if (!in_array($Event->getSportId(), $sport_ids))
                $sport_ids[] = $Event->getSportId();

            $List[$Event->getSportId()][] = $Event;
        }
        unset($Events);

        $criteria = new CDbCriteria();
        $criteria->order = '`t`.title asc';
        $criteria->addInCondition('id', $sport_ids);
        /** @var \Sport[] $models */
        $models = \Sport::model()->findAll($criteria);

        $params = [
            'Events' => $List,
            'Sports' => $models,
            'pages' => $pages,
        ];

        $this->getController()->setPageTitle('Баскетбол');
        if (Yii::app()->getRequest()->getIsAjaxRequest()) {
            $view = 'index';
            if($pages->getCurrentPage() > 0)
                $view = 'page/index';

            Yii::app()->getAjax()
                ->addTrigger('page:loaded')
                ->addReplace($view, '#content-replacement', $params)
                ->send();
        } else
            $this->getController()->render('index', $params);
    }
}