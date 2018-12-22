<?php
namespace frontend\modules\admin\controllers\actions\event;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use common\components\NException;
use common\interfaces\iStatus;
use Yii;
use Sport;

class ControlAction extends CAction
{
    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        $event_id = Yii::app()->getRequest()->getParam('event_id');
        $line_id = Yii::app()->getRequest()->getParam('line_id');
        /** @var \Sport $Sport */
        $Sport = \Sport::model()->findByPk($line_id);
        if (!$Sport)
            Yii::app()->getAjax()->addErrors('Линия не найдена')->send();

        $criteria = new \CDbCriteria();
        $criteria->addCondition('`t`.id = :id');
        $criteria->addCondition('`t`.status != :finish');
        //$criteria->with = ['eventResult' => ['joinType' => 'left join']];
        $criteria->params = [':id' => $event_id, ':finish' => iStatus::STATUS_FINISH];

        /** @var \SportEvent $SportEvent */
        $SportEvent = \SportEvent::model()->find($criteria);
        if (!$SportEvent)
            Yii::app()->getAjax()->addErrors('Событие не найдено')->send();

        //@TODO fixed
        $EventResult = new \SportEventResult();

        $post = Yii::app()->getRequest()->getPost('SportEvent');
        //$postResult = Yii::app()->getRequest()->getPost('SportEventResult');
        if ($post) {
            $t = Yii::app()->db->beginTransaction();
            try {
                /*if (isset($post['have_result']) && $post['have_result'] == 1) {
                    $EventResult->setAttributes($postResult);
                    $EventResult->setEventId($SportEvent->getId());

                    if ($EventResult->getTeam1Part1() > $EventResult->getTeam1Part2() || $EventResult->getTeam2Part1() > $EventResult->getTeam2Part2()) {
                        Yii::app()->getAjax()->addErrors("Некорректно проставленные голы");
                        throw new NException('Некорректно проставленные голы', NException::ERROR_EVENT_RESULT);
                    }

                    if (!$EventResult->save()) {
                        Yii::app()->getAjax()->addErrors($EventResult);
                        throw new NException('Неудалось зафиксировать ручной результат события', NException::ERROR_EVENT_RESULT, [
                            'errors' => $EventResult->getErrors(),
                            'attr' => $EventResult->getAttributes()
                        ]);
                    }

                    $SportEvent->setStatus(iStatus::STATUS_FINISH);
                }*/

                $SportEvent->setAttributes($post);
                $SportEvent->onAfterEventChange = [new \AdminHistory(), 'afterChange'];
                if (!$SportEvent->updateAction()) {
                    Yii::app()->getAjax()->addErrors($SportEvent);
                    throw new NException('Неудалось сохранить событие из управления', NException::ERROR_EVENT, [
                        'errors' => $SportEvent->getErrors(),
                        'attr' => $SportEvent->getAttributes()
                    ]);
                }

                $t->commit();

                Yii::app()->getAjax()->addMessage('Событие сохранено')
                    ->runJS('closeModal');
            } catch (\Exception $ex) {
                $t->rollback();
                \MException::logMongo($ex);
            }

            Yii::app()->getAjax()->send();
        }

        $params = [
            'sport' => $Sport,
            'model' => $SportEvent,
            'result' => $EventResult,
            'line_id' => $line_id,
        ];

        Yii::app()->getStatic()->setWww()->registerScriptFile('events.js');
        Yii::app()->getAjax()
            ->addReplace('_control', '#customModal #replacement', $params)
            ->runJS('checkControl')
            ->runJS('openCustom')->send();
    }
}