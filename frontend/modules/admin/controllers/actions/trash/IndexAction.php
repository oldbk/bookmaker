<?php
namespace frontend\modules\admin\controllers\actions\trash;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use Yii;
use Sport;
use CDbCriteria;

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
        $criteria = new \CDbCriteria();
        $criteria->scopes = ['trash', 'feature'];
        $criteria->index = 'id';
        $criteria->order = '`t`.date_int desc';

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

        $List = [];
        $sport_ids = [];
        foreach ($EventList as $Event) {
            if (isset($new_ratio_list[$Event->getId()]))
                $Event->getNewRatio()->populateRecord($new_ratio_list[$Event->getId()]);

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
            'sports' => $models,
            'EventList' => $List,
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