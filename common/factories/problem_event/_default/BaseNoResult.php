<?php
/**
 * Created by PhpStorm.
 */

namespace common\factories\problem_event\_default;

use common\factories\problem_event\_default\_base\BaseEventProblem;
use common\factories\problem_event\_default\_interface\iProblemEvent;
use common\sport\result\iResult;
use SportEventProblem;

class BaseNoResult extends BaseEventProblem implements iProblemEvent
{
    /** @var iResult */
    protected $ResultEvent;

    /**
     * @param $SportEvent
     * @param null|SportEventProblem $Problem
     */
    public function __construct(&$SportEvent, &$Problem)
    {
        parent::__construct($SportEvent, $Problem);
        $this->setResultEvent($SportEvent->getResult());
    }

    /**
     * @return iResult
     */
    public function getResultEvent()
    {
        return $this->ResultEvent;
    }

    /**
     * @param iResult $ResultEvent
     * @return $this
     */
    public function setResultEvent($ResultEvent)
    {
        $this->ResultEvent = $ResultEvent;
        return $this;
    }

    public function getProblemType()
    {
        return SportEventProblem::PROBLEM_NO_RESULT;
    }

    public function checkFieldList()
    {
        return [];
    }

    public function hasProblem()
    {
        return $this->getResultEvent()->isEmpty();
    }

    public function checkSameProblem($problems)
    {
        return false;
    }

    public function checkProblem()
    {
        $isProblem = $this->hasProblem();
        echo sprintf("Have problem: %s\n", $isProblem ? 'TRUE' : 'FALSE');

        $this->setIsProblem($isProblem);
        if($isProblem && !$this->getProblemEvent()) {
            echo sprintf("Event %d no result. Create.\n", $this->getSportEvent()->getId());
            return $this->setIsProblem(true)
                ->create('Неудалось получить результат события');
        } elseif(!$isProblem && $this->getProblemEvent()) {
            echo sprintf("Event %d no result. Resolve.\n", $this->getSportEvent()->getId());
            return $this
                ->setIsProblem(false)
                ->resolve();
        }

        return true;
    }
}