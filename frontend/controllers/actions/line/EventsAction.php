<?php
namespace frontend\controllers\actions\line;

use CAction;
use frontend\components\FrontendController;
use Yii;
use Sport;

/**
 * Class EventsAction
 * @package frontend\controllers\actions\line
 *
 * @method FrontendController getController()
 */
class EventsAction extends CAction
{
    public $admin_part = false;

    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        $line = Yii::app()->getRequest()->getParam('line_id');
        /** @var Sport $Sport */
        $Sport = Sport::model()->findByPk($line);
        if (!$Sport)
            Yii::app()->getAjax()->addErrors('Вид спорта не найден')->send();

        $criteria = new \CDbCriteria();
        $criteria->order = '`t`.date_int asc';
        $criteria->scopes = ['for_user'];
        $criteria->index = 'id';
        $criteria->addCondition('`t`.sport_id = :id');
        $criteria->params = [':id' => $line];

        /** @var \SportEvent[] $models */
        $models = \SportEvent::model()->findAll($criteria);

        $criteria = new \CDbCriteria();
        $criteria->select = ['event_id', 'type', 'value'];
        $criteria->addInCondition('`t`.event_id', array_keys($models));
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

        foreach (array_keys($models) as $key) {
            if (isset($new_ratio_list[$key]))
                $models[$key]->getNewRatio()->populateRecord($new_ratio_list[$key], true);
            if (isset($last_ratio_list[$key]))
                $models[$key]->getOldRatio()->populateRecord($last_ratio_list[$key], true);
        }

        $params = [
            'sport' => $Sport,
            'models' => $models
        ];

        $this->getController()->setPageTitle($Sport->getTitle());
        if (Yii::app()->getRequest()->isAjaxRequest)
            Yii::app()->getAjax()
                ->addReplace('events', '#content-replacement', $params)
                ->addTrigger('page:loaded')
                ->send();
        else {
            $this->getController()->render('events', $params);
        }
    }
}