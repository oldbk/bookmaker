<?php
namespace frontend\modules\admin\controllers\actions\problem\resolve;

/**
 * Created by PhpStorm.
 */
use CAction;
use common\factories\problem_event\_default\_interface\iProblemFora;
use common\factories\ProblemEventFactory;
use Yii;

class ForaAction extends CAction
{
    public function run()
    {
        $problem_id = Yii::app()->getRequest()->getParam('problem_id');
        $criteria = new \CDbCriteria();
        $criteria->addCondition('`t`.id = :id');
        $criteria->addCondition('`t`.problem_type = :problem_type');
        $criteria->scopes = ['notResolve'];
        $criteria->params = [':id' => $problem_id, ':problem_type' => \SportEventProblem::PROBLEM_FORA];
        /** @var \SportEventProblem $ProblemEvent */
        $ProblemEvent = \SportEventProblem::model()->find($criteria);
        if (!$ProblemEvent) {
            Yii::app()->getAjax()->addErrors('Проблема для этого события не найдена');
            throw new \Exception();
        }

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
                foreach ($post as $key => $value)
                    $new_ratio_list[$key] = $value;

                $Event->getNewRatio()->populateRecord($new_ratio_list);
                $Event->getOldRatio()->populateRecord($last_ratio_list);
                if (!$Event->haveDiff())
                    Yii::app()->getAjax()->addErrors('Новые значения не отличаются от старых')->send();

                $factory_params = ['SportEvent' => $Event, 'Problem' => $ProblemEvent];
                $ProblemEventFactory = ProblemEventFactory::factory($ProblemEvent->getProblemType(), $factory_params);
                if ($ProblemEventFactory->hasProblem()) {
                    Yii::app()->getAjax()->addErrors('Некорректные значения фор');
                    throw new \Exception();
                }

                /** @var iProblemFora $NewRatio */
                $NewRatio = $Event->getNewRatio();
                $ProblemEventFactory->resolve(Yii::app()->getUser()->getId(), [
                    'set_fora1' => $NewRatio->getForaVal1(),
                    'set_fora2' => $NewRatio->getForaVal2(),
                ]);

                $problem_count = $Event->getProblemCount() - 1;
                $r = $Event->setProblemCount($problem_count)
                    ->setHaveProblem($problem_count == 0 ? false : true)
                    ->save();
                if (!$r) {
                    Yii::app()->getAjax()->addErrors($Event);
                    throw new \Exception();
                }

                $criteria = new \CDbCriteria();
                $criteria->addCondition('event_id = :event_id');
                $criteria->addCondition('ratio_name = :ratio_name or ratio_name = :ratio_name2');
                $criteria->index = 'ratio_name';
                $criteria->params = [
                    ':event_id' => $Event->getId(),
                    ':ratio_name' => 'fora_val_1',
                    ':ratio_name2' => 'fora_val_2',
                ];
                /** @var \SportEventFixedValue[] $FixedRatio */
                $FixedRatio = \SportEventFixedValue::model()->findAll($criteria);
                foreach (['fora_val_1', 'fora_val_2'] as $field) {
                    $ForaFixed = isset($FixedRatio[$field]) ? $FixedRatio[$field] : new \SportEventFixedValue();
                    $ForaFixed->setEventId($Event->getId())
                        ->setRatioName($field)
                        ->setRatioValue($post[$field])
                        ->setUserId(Yii::app()->getUser()->getId());
                    if (!$ForaFixed->save()) {
                        Yii::app()->getAjax()->addErrors($ForaFixed);
                        throw new \Exception();
                    }
                }

                if ($Event->haveDiff()) {
                    $Event->setV($Event->getV() + 1);
                    $Event->getNewRatio()->insert($Event->getId(), $Event->getV());
                }

                $AdminLog = new \AdminHistory();
                $AdminLog->setAdminId(Yii::app()->getUser()->getId())
                    ->setActionId(\AdminHistory::ACTION_PROBLEM_RESOLVE)
                    ->setItemId($Event->getId())
                    ->setDescription('Решили проблему с форой')
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
            $Event = \SportEvent::model()->findByPk($ProblemEvent->getEventId());
            if(!$Event)
                Yii::app()->getAjax()->addErrors('Не удалось найти событие')->send();

            $params = ['Event' => $Event];
            Yii::app()->getAjax()
                ->addReplace(sprintf('resolve/%s', $ProblemEvent->getProblemType()), '#customModal #replacement', $params)
                ->runJS('checkControl')
                ->runJS('openCustom')->send();
        }
    }
}