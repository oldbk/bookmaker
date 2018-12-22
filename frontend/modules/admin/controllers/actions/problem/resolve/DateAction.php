<?php
namespace frontend\modules\admin\controllers\actions\problem\resolve;

/**
 * Created by PhpStorm.
 */
use CAction;
use common\components\NException;
use common\factories\ProblemEventFactory;
use Yii;
use common\helpers\fixed\Validate as FixedValidate;
use common\helpers\fixed\Value as FixedValue;

class DateAction extends CAction
{
    public function run()
    {
        $problem_id = Yii::app()->getRequest()->getParam('problem_id');
        $criteria = new \CDbCriteria();
        $criteria->addCondition('`t`.id = :id');
        $criteria->addCondition('`t`.problem_type = :problem_type');
        $criteria->scopes = ['notResolve'];
        $criteria->params = [':id' => $problem_id, ':problem_type' => \SportEventProblem::PROBLEM_DATE];
        /** @var \SportEventProblem $ProblemEvent */
        $ProblemEvent = \SportEventProblem::model()->find($criteria);
        if (!$ProblemEvent)
            Yii::app()->getAjax()->addErrors('Проблема для этого события не найдена')->send();

        $post = Yii::app()->getRequest()->getPost('SportEvent');
        if ($post) {
            $t = Yii::app()->getDb()->beginTransaction();
            try {
                /** @var \iSportEvent $Event */
                $Event = \SportEvent::model()->findByPk($ProblemEvent->getEventId());
                if(!$Event) {
                    Yii::app()->getAjax()->addErrors('Не удалось найти событие')->send();
                    throw new \Exception();
                }

                $FixedValue = new FixedValue();
                $FixedValidate = new FixedValidate();
                if (($error = $FixedValidate->date($post['date_string'])) !== true) {
                    Yii::app()->getAjax()->addErrors($error);
                    throw new NException();
                }

                $FixedValue->dateField('date', $post['date_string']);
                foreach ($FixedValue->getFixed() as $f => $v)
                    $Event->setAttribute($f, $v);

                $factory_params = ['SportEvent' => $Event, 'Problem' => $ProblemEvent];
                $ProblemEventFactory = ProblemEventFactory::factory($Event->getSportType(), $ProblemEvent->getProblemType(), $factory_params);
                $ProblemEventFactory->resolve(Yii::app()->getUser()->getId(), ['set_date_int' => $Event->getDateInt()]);

                $problem_count = $Event->getProblemCount() - 1;
                $r = $Event->setProblemCount($problem_count)
                    ->setHaveProblem($problem_count == 0 ? false : true)
                    ->save();
                if (!$r) {
                    Yii::app()->getAjax()->addErrors($Event);
                    throw new NException();
                }

                $criteria = new \CDbCriteria();
                $criteria->addCondition('event_id = :event_id');
                $criteria->addCondition('ratio_name = :ratio_name');
                $criteria->params = [':event_id' => $Event->getId(), ':ratio_name' => 'date'];
                $FixedRatio = \SportEventFixedValue::model()->find($criteria);
                if (!$FixedRatio)
                    $FixedRatio = new \SportEventFixedValue();
                $FixedRatio->setEventId($Event->getId())
                    ->setRatioName('date')
                    ->setRatioValue($post['date_string'])
                    ->setUserId(Yii::app()->getUser()->getId());
                if (!$FixedRatio->save()) {
                    Yii::app()->getAjax()->addErrors($FixedRatio);
                    throw new \Exception();
                }

                $AdminLog = new \AdminHistory();
                $AdminLog->setAdminId(Yii::app()->getUser()->getId())
                    ->setActionId(\AdminHistory::ACTION_PROBLEM_RESOLVE)
                    ->setItemId($Event->getId())
                    ->setDescription('Решили проблему с датой')
                    ->setCreateAt(time());
                if(!$AdminLog->save()) {
                    Yii::app()->getAjax()->addErrors($AdminLog);
                    throw new \Exception();
                }

                $t->commit();

                $criteria = new \CDbCriteria();
                $criteria->scopes = ['haveProblem', 'nTrash'];
                $error_count = \SportEvent::model()->count($criteria);
                Yii::app()->getAjax()->runJS('updateProblemCount', [$error_count]);

                Yii::app()->getAjax()->addMessage('Проблема решена')
                    ->runJS('closeModal')
                    ->runJS('updatePage', Yii::app()->getRequest()->getUrlReferrer());
            } catch (\Exception $ex) {
                $t->rollback();
                \MException::logMongo($ex);
            }

            Yii::app()->getAjax()->send();
        } else {
            $Event = \SportEvent::model()->findByPk($ProblemEvent->getEventId());
            if(!$Event)
                Yii::app()->getAjax()->addErrors('Не удалось найти событие')->send();

            $params = ['Event' => $Event];
            Yii::app()->getAjax()
                ->addReplace(sprintf('resolve/%s', $ProblemEvent->getProblemType()), '#customModal #replacement', $params)
                ->runJS('openCustom')->send();
        }
    }
}