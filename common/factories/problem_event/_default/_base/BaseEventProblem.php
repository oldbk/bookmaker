<?php
namespace common\factories\problem_event\_default\_base;
use common\components\NException;
use SportEventProblem;

/**
 * Created by PhpStorm.
 */

abstract class BaseEventProblem
{
    /** @var \SportEvent */
    protected $sport_event;

    /** @var \SportEventProblem */
    protected $problem_event;

    /** @var bool */
    protected $is_problem = false;

    /** @var bool */
    protected $is_new = false;

    /**
     * @param \SportEvent $SportEvent
     * @param null|SportEventProblem $Problem
     */
    public function __construct(&$SportEvent, &$Problem)
    {
        $this->setSportEvent($SportEvent)
            ->setProblemEvent($Problem);
    }

    abstract public function getProblemType();

    public function resolve($resolver_id = null, $params = [])
    {
        $auto_resolved = $resolver_id ? false : true;

        $t = null;
        if(\Yii::app()->db->getCurrentTransaction() === null)
            $t = \Yii::app()->db->beginTransaction();

        try {
            $params = \CMap::mergeArray(unserialize($this->getProblemEvent()->getCustom()), $params);

            $r = $this->getProblemEvent()
                ->setIsResolved(true)
                ->setResolverId($resolver_id)
                ->setAutoResolved($auto_resolved)
                ->setCustom(serialize($params))
                ->save();
            if(!$r) {
                throw new NException('Не удалось решить проблему', NException::ERROR_PROBLEM, [
                    'attributes' => $this->getProblemEvent()->getAttributes(),
                    'errors' => $this->getProblemEvent()->getErrors(),
                ]);
            }

            if($t) $t->commit();

            return true;
        } catch (\Exception $ex) {
            if($t) $t->rollback();
            $this->exception($ex);
        }

        return false;
    }

    public function create($desc, $params = [])
    {
        $t = null;
        if(\Yii::app()->db->getCurrentTransaction() === null)
            $t = \Yii::app()->db->beginTransaction();

        try {
            $model = new SportEventProblem();
            $r = $model->setEventId($this->getSportEvent()->getId())
                ->setProblemType($this->getProblemType())
                ->setDescription($desc)
                ->setStatusBefore($this->getSportEvent()->getStatus())
                ->setIsFreeze($this->getSportEvent()->isFreeze())
                ->setCustom(serialize($params))
                ->save();
            if(!$r)
                throw new NException('Не удалось создать проблему', NException::ERROR_PROBLEM, [
                    'attributes' => $model->getAttributes(),
                    'errors' => $model->getErrors(),
                ]);

            if($t) $t->commit();

            $this
                ->setIsNew(true)
                ->setProblemEvent($model);
            return true;
        } catch (\Exception $ex) {
            if($t) $t->rollback();
            $this->exception($ex);
        }

        return false;
    }

    /**
     * @return \SportEvent
     */
    public function getSportEvent()
    {
        return $this->sport_event;
    }

    /**
     * @param \SportEvent $sport_event
     * @return $this
     */
    public function setSportEvent(&$sport_event)
    {
        $this->sport_event = $sport_event;
        return $this;
    }

    /**
     * @return \SportEventProblem
     */
    public function getProblemEvent()
    {
        return $this->problem_event;
    }

    /**
     * @param \SportEventProblem $problem_event
     * @return $this
     */
    public function setProblemEvent(&$problem_event)
    {
        $this->problem_event = $problem_event;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isProblem()
    {
        return $this->is_problem;
    }

    /**
     * @param boolean $is_problem
     * @return $this
     */
    public function setIsProblem($is_problem)
    {
        $this->is_problem = $is_problem;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isNew()
    {
        return $this->is_new;
    }

    /**
     * @param boolean $is_new
     * @return $this
     */
    public function setIsNew($is_new)
    {
        $this->is_new = $is_new;
        return $this;
    }

    protected function exception($ex)
    {
        \MException::logMongo($ex, 'problem_factory');
    }
}