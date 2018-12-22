<?php
namespace frontend\controllers\actions\site;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use common\components\NException;
use common\interfaces\iStatus;
use Yii;
use CAction;
use common\components\VarDumper;

error_reporting(E_ALL);
ini_set("display_errors", 1);

class FixAction extends CAction
{
    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        $criteria = new \CDbCriteria();
        $criteria->limit = 300;
        $criteria->order = 'id desc';
        $criteria->addCondition('status != :finish');
        $criteria->params = [
            ':finish' => iStatus::STATUS_FINISH
        ];

        /** @var \SportEvent[] $Sport */
        $Sport = \SportEvent::model()->findAll($criteria);
        foreach ($Sport as $Item) {
            $t = Yii::app()->db->beginTransaction();
            try {
                $criteria = new \CDbCriteria();
                $criteria->addCondition('team_1 = :team_1');
                $criteria->addCondition('team_2 = :team_2');
                $criteria->addCondition('id != :id');
                $criteria->addCondition('status != :finish');
                $criteria->params = [
                    ':team_1' => $Item->getTeam1(),
                    ':team_2' => $Item->getTeam2(),
                    ':id' => $Item->getId(),
                    ':finish' => iStatus::STATUS_FINISH,
                ];
                /** @var \SportEvent[] $_sport */
                $_sport = \SportEvent::model()->findAll($criteria);
                foreach ($_sport as $_item) {
                    $_item
                        ->setHaveResult(1)
                        ->setStatus(iStatus::STATUS_FINISH)
                        ->save();

                    $EventResult = $_item->getResult();
                    $EventResult->populateRecord(['is_cancel' => true]);

                    if (!$EventResult->insert($_item->getId())) {
                        Yii::app()->getAjax()->addErrors('Не удалось зафиксировать результат');
                        throw new NException();
                    }
                }

                $t->commit();
            } catch (\Exception $ex) {
                $t->rollback();
                var_dump('error');
            }
        }

        var_dump('done');
    }
}