<?php
namespace frontend\modules\admin\controllers\actions\event;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use Yii;
use SportEvent;

class InfoAction extends CAction
{
    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        $criteria = new \CDbCriteria();
        $criteria->addCondition('id = :id');
        $criteria->params = [':id' => Yii::app()->getRequest()->getParam('event_id')];
        /** @var SportEvent $Event */
        $Event = SportEvent::model()->find($criteria);
        if (!$Event)
            Yii::app()->getAjax()->addErrors('Событие не найдено')->send();

        $criteria = new \CDbCriteria();
        $criteria->select = ['type', 'value'];
        $criteria->addCondition('`t`.event_id = :event_id');
        $criteria->addCondition('`t`.position = :position');
        $criteria->params[':event_id'] = $Event->getId();
        $criteria->params[':position'] = \SportEventRatio::POSITION_NEW;
        $new_ratio = \SportEventRatio::model()
            ->getCommandBuilder()
            ->createFindCommand(\SportEventRatio::model()->tableName(), $criteria)
            ->queryAll();
        $new_ratio_list = [];
        foreach ($new_ratio as $ratio)
            $new_ratio_list[$ratio['type']] = $ratio['value'];

        $criteria->params[':position'] = \SportEventRatio::POSITION_LAST;
        $last_ratio = \SportEventRatio::model()
            ->getCommandBuilder()
            ->createFindCommand(\SportEventRatio::model()->tableName(), $criteria)
            ->queryAll();
        $last_ratio_list = [];
        foreach ($last_ratio as $ratio)
            $last_ratio_list[$ratio['type']] = $ratio['value'];

        $Event->getOldRatio()->populateRecord($last_ratio_list);
        $Event->getNewRatio()->populateRecord($new_ratio_list);

        Yii::app()->getAjax()
            ->addReplace('_info', '#customModal #replacement', ['model' => $Event])
            ->runJS('openCustom')->send();
    }
}