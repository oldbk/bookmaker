<?php
namespace frontend\modules\admin\controllers\actions\problem;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use common\factories\ProblemEventFactory;
use Yii;

class IgnoreAction extends CAction
{
    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        $problem_id = Yii::app()->getRequest()->getParam('problem_id');

        $t = Yii::app()->getDb()->beginTransaction();
        try {
            $criteria = new \CDbCriteria();
            $criteria->addCondition('`t`.id = :id');
            $criteria->scopes = ['notResolve'];
            $criteria->with = ['event'];
            $criteria->params = [':id' => $problem_id];
            /** @var \SportEventProblem $ProblemEvent */
            $ProblemEvent = \SportEventProblem::model()->find($criteria);
            if (!$ProblemEvent) {
                Yii::app()->getAjax()->addErrors('Проблема для этого события не найдена');
                throw new \Exception();
            }

            $Event = $ProblemEvent->event;

            $params = ['SportEvent' => $Event, 'Problem' => $ProblemEvent];
            $ProblemEventFactory = ProblemEventFactory::factory($Event->getSportType(), $ProblemEvent->getProblemType(), $params);
            $ProblemEventFactory->resolve(Yii::app()->getUser()->getId());

            $problem_count = $Event->getProblemCount() - 1;
            $r = $Event->setProblemCount($problem_count)
                ->setHaveProblem($problem_count == 0 ? false : true)
                ->save();
            if (!$r) {
                Yii::app()->getAjax()->addErrors($Event);
                throw new \Exception();
            }

            $t->commit();

            Yii::app()->getAjax()->addMessage('Проблема будет игнорироваться')
                ->runJS('updatePage', Yii::app()->getRequest()->getUrlReferrer());
        } catch (\Exception $ex) {
            $t->rollback();
            \MException::logMongo($ex);
        }

        Yii::app()->getAjax()->send();
    }
}