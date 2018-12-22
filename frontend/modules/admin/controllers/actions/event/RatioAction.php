<?php
namespace frontend\modules\admin\controllers\actions\event;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use common\factories\DataToViewFactory;
use Yii;
use CDbCriteria;
use common\helpers\fixed\Value as FixedValue;
use common\helpers\fixed\Validate as FixedValidate;

class RatioAction extends CAction
{
    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        $event = Yii::app()->getRequest()->getParam('event_id');

        $t = Yii::app()->getDb()->beginTransaction();
        try {
            /** @var \iSportEvent $Event */
            $Event = \SportEvent::model()->with('sport')->findByPk($event);
            if (!$Event) {
                Yii::app()->getAjax()->addErrors('Событие не найдено');
                throw new \Exception();
            }

            $field = trim(Yii::app()->getRequest()->getParam('name'));
            $value = trim(Yii::app()->getRequest()->getParam('value'));

            $FixedRatio = new FixedValue();
            $FixedValidate = new FixedValidate();
            if (($error = $FixedValidate->{$field}($value)) !== true) {
                Yii::app()->getAjax()->addErrors($error);
                throw new \Exception();
            }

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
            $last_ratio_list = $new_ratio_list = [];
            foreach ($new_ratio as $ratio) {
                $new_ratio_list[$ratio['type']] = $ratio['value'];
                $last_ratio_list[$ratio['type']] = $ratio['value'];
            }

            $DataToView = DataToViewFactory::factory($Event->getSportType(), ['Event' => $Event]);
            $message = sprintf("Добавил фиксацию для коэффициента %s со значением %s", $DataToView->getResultLabel($field), $value);
            $FixedRatio->{$field}($field, $value);
            foreach ($FixedRatio->getFixed() as $f => $v)
                $new_ratio_list[$f] = $v;

            $Event->onAfterEventRatioChange = [new \AdminHistory($message), 'afterRatioChange'];
            if (!$Event->updateAction()) {
                Yii::app()->getAjax()->addErrors($Event);
                throw new \Exception();
            }

            $criteria = new CDbCriteria();
            $criteria->addCondition('event_id = :event_id');
            $criteria->addCondition('ratio_name = :ratio_name');
            $criteria->params = [':event_id' => $Event->getId(), ':ratio_name' => $field];
            $FixedRatio = \SportEventFixedValue::model()->find($criteria);
            if (!$FixedRatio)
                $FixedRatio = new \SportEventFixedValue();
            $FixedRatio->setEventId($Event->getId())
                ->setRatioName($field)
                ->setRatioValue($value)
                ->setUserId(Yii::app()->getUser()->getId());
            if (!$FixedRatio->save()) {
                Yii::app()->getAjax()->addErrors($FixedRatio);
                throw new \Exception();
            }

            $Event->getNewRatio()->populateRecord($new_ratio_list);
            $Event->getOldRatio()->populateRecord($last_ratio_list);
            if ($Event->haveDiff()) {
                $Event->setV($Event->getV() + 1);
                $Event->getNewRatio()->insert($Event->getId(), $Event->getV());
            }

            $Event->save();
            $t->commit();

            Yii::app()->getAjax()
                ->addMessage('Коэффициент упешно изменен');

        } catch (\Exception $ex) {
            $t->rollback();
            \MException::logMongo($ex);
        }

        Yii::app()->getAjax()->send();
    }
}