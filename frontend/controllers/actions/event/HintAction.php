<?php
namespace frontend\controllers\actions\event;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use common\factories\RatioFactory;
use common\helpers\SportHelper;
use Yii;
use CDbCriteria;

class HintAction extends CAction
{
    public $admin_part = false;

    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        $event_id = Yii::app()->getRequest()->getParam('event_id');
        $field = Yii::app()->getRequest()->getParam('field');

        $criteria = new CDbCriteria();
        $criteria->addCondition('id = :id');
        $criteria->params = [':id' => $event_id];
        /** @var \iSportEvent $SportEvent */
        $SportEvent = \SportEvent::model()->find($criteria);
        if (!$SportEvent)
            Yii::app()->getAjax()->addErrors('Не удалось найти событие')->send();

        $criteria = new \CDbCriteria();
        $criteria->select = ['type', 'value'];
        $criteria->addCondition('`t`.event_id = :event_id');
        $criteria->addCondition('`t`.position = :position');
        $criteria->params[':event_id'] = $SportEvent->getId();
        $criteria->params[':position'] = \SportEventRatio::POSITION_NEW;
        $new_ratio = \SportEventRatio::model()
            ->getCommandBuilder()
            ->createFindCommand(\SportEventRatio::model()->tableName(), $criteria)
            ->queryAll();
        $new_ratio_list = [];
        foreach ($new_ratio as $ratio)
            $new_ratio_list[$ratio['type']] = $ratio['value'];

        $SportEvent->getNewRatio()->populateRecord($new_ratio_list, true);

        $hint = null;
        if (preg_match('/_static/ui', $field)) {
            $hint = true;
            $field = SportHelper::getByID($SportEvent->getSportType()).'_'.$field;
        } else {
            $RatioFactory = RatioFactory::factory($SportEvent->getSportType(), $field);
            $hint = $RatioFactory->setEvent($SportEvent)
                ->setRatioValue($SportEvent->getNewRatio()->getAttribute($field))
                ->getHint();
        }

        if (!$hint)
            Yii::app()->getAjax()->addErrors('Подсказка не найдена')->send();

        Yii::app()->getAjax()
            ->addReplace('hint/_' . $field, '#customModal #replacement', ['hint' => $hint, 'model' => $SportEvent])
            ->runJS('openCustom')->send();
    }
}