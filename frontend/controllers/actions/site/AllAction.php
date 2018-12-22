<?php
namespace frontend\controllers\actions\site;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use Yii;
use CDbCriteria;

class AllAction extends CAction
{
    public function run()
    {
        $criteria = new CDbCriteria();
        $criteria->index = 'id';
        $criteria->scopes = ['for_user'];
        $criteria->with = ['sport'];
        $criteria->order = '`t`.date_int asc';

        $dep1 = new \CDbCacheDependency('SELECT MAX(update_at) FROM {{sport_event}}');
        $dep1->reuseDependentData = true;;

        /** @var \SportEvent[] $All */
        $All = \SportEvent::model()->cache(3600, $dep1)->findAll($criteria);

        $criteria = new \CDbCriteria();
        $criteria->select = ['event_id', 'type', 'value'];
        $criteria->addInCondition('`t`.event_id', array_keys($All));
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

        foreach (array_keys($All) as $key) {
            if (isset($new_ratio_list[$key]))
                $All[$key]->getNewRatio()->populateRecord($new_ratio_list[$key], true);
            if (isset($last_ratio_list[$key]))
                $All[$key]->getOldRatio()->populateRecord($last_ratio_list[$key], true);
        }


        $params = [
            'models' => $All,
        ];

        Yii::app()->getAjax()
            ->addReplace('_all', '#content-replacement #replace-front-all', $params)
            ->addTrigger('page:loaded')
            ->send();
    }
}