<?php
use \common\interfaces\iStatus;
use \common\factories\TransferFactory;

/**
 * Created by PhpStorm.
 */
class PrepareCommand extends CConsoleCommand
{
    public function actionFix()
    {
        $criteria = new CDbCriteria();
        $criteria->addCondition('date_int > :time');
        $criteria->scopes = ['haveProblem'];
        $criteria->params = [':time' => time()];
        /** @var SportEvent[] $Events */
        $Events = SportEvent::model()->findAll($criteria);
        foreach ($Events as $Event)
        {
            $t = Yii::app()->getDb()->beginTransaction();
            try {
                $r = $Event->setHaveProblem(0)
                    ->setProblemCount(0)
                    ->save();
                if(!$r)
                    throw new Exception('Ошибка');

                $criteria = new CDbCriteria();
                $criteria->addCondition('`t`.event_id = :event_id');
                $criteria->params = [':event_id' => $Event->getId()];
                /** @var SportEventProblem[] $Problem */
                $Problem = SportEventProblem::model()->findAll($criteria);
                foreach ($Problem as $Item) {
                    $r = $Item->setIsResolved(true)
                        ->save();
                    if(!$r)
                        throw new Exception('Ошибка');
                }

                $t->commit();
            } catch (Exception $ex) {
                $t->rollback();
                var_dump($ex->getMessage());
            }
        }

        var_dump('finish');
    }

    public function actionEvent()
    {
        $criteria = new CDbCriteria();
        $criteria->select = ['id'];
        $SportEvent = SportEvent::model()
            ->getCommandBuilder()
            ->createFindCommand(SportEvent::model()->tableName(), $criteria)
            ->query();

        foreach($SportEvent as $key => $Event) {
            if ($key % 100 == 0)
                var_dump('[' . date('d.m.Y H:i:s') . '] ' . $key);

            $criteria = new CDbCriteria();
            $criteria->select = ['*', 'max(_v)'];
            $criteria->addCondition('sport_event_id = :sport_event_id');
            $criteria->params = [':sport_event_id' => $Event['id']];
            $SportVersion = SportEventPrevious::model()
                ->getCommandBuilder()
                ->createFindCommand(SportEventPrevious::model()->tableName(), $criteria)
                ->queryRow();

            SportEvent::model()->updateByPk($Event['id'], ['_v' => $SportVersion['max(_v)'], 'update_at' => time()]);
        }
    }

    public function actionRatio()
    {
        $mapping = [
            'fora_val_1',
            'fora_val_2',
            'fora_ratio_1',
            'fora_ratio_2',
            'total_val',
            'total_more',
            'total_less',
            'ratio_p1',
            'ratio_x',
            'ratio_p2',
            'ratio_1x',
            'ratio_12',
            'ratio_x2',
            'itotal_val_1',
            'itotal_val_2',
            'itotal_more_1',
            'itotal_more_2',
            'itotal_less_1',
            'itotal_less_2',
        ];

        $builder = Yii::app()->getDb()->getSchema()->getCommandBuilder();

        $criteria = new CDbCriteria();
        $criteria->select = ['id', '_v'];
        $criteria->addCondition('id > 4338');
        $SportEvent = SportEvent::model()
            ->getCommandBuilder()
            ->createFindCommand(SportEvent::model()->tableName(), $criteria)
            ->query();
        $i = 0;
        foreach ($SportEvent as $Event) {
            $criteria = new CDbCriteria();
            $criteria->addCondition('sport_event_id = :sport_event_id');
            $criteria->params = [':sport_event_id' => $Event['id']];
            $SportEventVersion = SportEventPrevious::model()
                ->getCommandBuilder()
                ->createFindCommand(SportEventPrevious::model()->tableName(), $criteria)
                ->query();

            $values = [];
            foreach ($SportEventVersion as $Version) {
                $i++;
                if($i % 500 == 0) var_dump('['.date('d.m.Y H:i:s').'] '.$i);

                $position = SportEventRatio::POSITION_OLD;
                if($Version['_v'] == $Event['_v'])
                    $position = SportEventRatio::POSITION_NEW;
                elseif(($Event['_v'] - 1) == $Version['_v'])
                    $position = SportEventRatio::POSITION_LAST;

                foreach ($mapping as $field) {
                    if(!is_numeric($Version[$field]))
                        continue;

                    $values[] = [
                        'event_id' => $Version['sport_event_id'],
                        'type' => $field,
                        '_v' => $Version['_v'],
                        'value' => $Version[$field],
                        'position' => $position,
                        'update_at' => time(),
                        'create_at' => time(),
                    ];
                }
            }
            if(empty($values))
                continue;

            $t = Yii::app()->getDb()->beginTransaction();
            try {
                $command=$builder->createMultipleInsertCommand(SportEventRatio::model()->tableName(), $values);
                $command->execute();

                $t->commit();
            } catch (Exception $ex) {
                $t->rollback();
                var_dump($ex->getMessage());die;
            }
        }
    }
}