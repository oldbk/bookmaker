<?php
namespace frontend\modules\admin\controllers\actions\problem\resolve;

/**
 * Created by PhpStorm.
 */
use CAction;
use common\components\NException;
use common\factories\ProblemEventFactory;
use common\interfaces\iStatus;
use Yii;

class NoResultAction extends CAction
{
    public function run()
    {
        $problem_id = Yii::app()->getRequest()->getParam('problem_id');
        $criteria = new \CDbCriteria();
        $criteria->addCondition('`t`.id = :id');
        $criteria->addCondition('`t`.problem_type = :problem_type');
        $criteria->scopes = ['notResolve'];
        $criteria->params = [':id' => $problem_id, ':problem_type' => \SportEventProblem::PROBLEM_NO_RESULT];
        /** @var \SportEventProblem $ProblemEvent */
        $ProblemEvent = \SportEventProblem::model()->find($criteria);
        if (!$ProblemEvent) {
            Yii::app()->getAjax()->addErrors('Проблема для этого события не найдена или уже решена')->send();
        }

        $criteriaEvent = new \CDbCriteria();
        $criteriaEvent->addCondition('id = :id');
        $criteriaEvent->addCondition('have_result = 0');
        $criteriaEvent->params[':id'] = $ProblemEvent->getEventId();

        $post = Yii::app()->getRequest()->getPost('Result');
        if ($post) {
            $t = Yii::app()->getDb()->beginTransaction();
            try {

                $Event = \SportEvent::model()->find($criteriaEvent);
                if(!$Event) {
                    Yii::app()->getAjax()->addErrors('Не удалось найти событие или проблема уже решена');
                    throw new \Exception();
                }

                $Event
                    ->setHaveResult(1)
                    ->setStatus(iStatus::STATUS_FINISH);

                $EventResult = $Event->getResult();
                $EventResult->populateRecord($post);

                if ((empty($EventResult->getTeam1Result()) && $EventResult->getTeam1Result() != 0)
                    || (empty($EventResult->getTeam2Result()) && $EventResult->getTeam2Result() != 0)) {

                    Yii::app()->getAjax()->addErrors("Некорректно проставленные голы");
                    throw new NException();
                }

                if (!$EventResult->insert($Event->getId())) {
                    Yii::app()->getAjax()->addErrors('Не удалось зафиксировать результат');
                    throw new NException();
                }

                $factory_params = [
                    'SportEvent' => $Event,
                    'Problem' => $ProblemEvent,
                ];
                $ProblemEventFactory = ProblemEventFactory::factory($Event->getSportType(), $ProblemEvent->getProblemType(), $factory_params);
                $ProblemEventFactory->resolve(Yii::app()->getUser()->getId(), $EventResult->getAttributes());

                $problem_count = $Event->getProblemCount() - 1;
                $r = $Event->setProblemCount($problem_count)
                    ->setHaveProblem($problem_count == 0 ? false : true)
                    ->save();
                if (!$r) {
                    Yii::app()->getAjax()->addErrors($Event);
                    throw new \Exception();
                }

                $AdminLog = new \AdminHistory();
                $AdminLog->setAdminId(Yii::app()->getUser()->getId())
                    ->setActionId(\AdminHistory::ACTION_PROBLEM_RESOLVE)
                    ->setItemId($Event->getId())
                    ->setDescription('Решили проблему с результатом')
                    ->setCreateAt(time());
                if(!$AdminLog->save()) {
                    Yii::app()->getAjax()->addErrors($AdminLog);
                    throw new \Exception();
                }

                $criteria = new \CDbCriteria();
                $criteria->scopes = ['haveProblem', 'nTrash'];
                $error_count = \SportEvent::model()->count($criteria);
                Yii::app()->getAjax()->runJS('updateProblemCount', [$error_count]);

                $t->commit();

                Yii::app()->getAjax()->addMessage('Проблема решена')
                    ->runJS('closeModal')
                    ->runJS('updatePage', Yii::app()->getRequest()->getUrlReferrer());
            } catch (\Exception $ex) {
                $t->rollback();
                \MException::logMongo($ex);
            }

            Yii::app()->getAjax()->send();
        } else {
            Yii::app()->getStatic()->setWww()->registerScriptFile('problem.js', \CClientScript::POS_END, !YII_DEBUG);

            $Event = \SportEvent::model()->find($criteriaEvent);
            if(!$Event)
                Yii::app()->getAjax()->addErrors('Не удалось найти событие или проблема уже решена')->send();

            $Event->getResult()->doEmpty();
            $params = ['Event' => $Event];
            Yii::app()->getAjax()
                ->addReplace(sprintf('resolve/%s', $ProblemEvent->getProblemType()), '#customModal #replacement', $params)
                ->runJS('checkControl')
                ->addTrigger('page:loaded')
                ->runJS('openCustom')->send();
        }
    }
}