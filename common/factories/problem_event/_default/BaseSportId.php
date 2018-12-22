<?php
/**
 * Created by PhpStorm.
 */

namespace common\factories\problem_event\_default;

use common\factories\problem_event\_default\_base\BaseEventProblem;
use common\factories\problem_event\_default\_interface\iProblemEvent;
use SportEventProblem;

class BaseSportId extends BaseEventProblem implements iProblemEvent
{
    /** @var int */
    protected $sport_id;

    /** @var string */
    protected $sport_title;

    /**
     * @param $SportEvent
     * @param null|SportEventProblem $Problem
     * @param $sport_id
     * @param $sport_title
     */
    public function __construct(&$SportEvent, &$Problem, $sport_id = null, $sport_title = null)
    {
        parent::__construct($SportEvent, $Problem);

        $this->setSportId($sport_id)
            ->setSportTitle($sport_title);
    }

    /**
     * @return int
     */
    public function getSportId()
    {
        return $this->sport_id;
    }

    /**
     * @param int $sport_id
     * @return $this
     */
    public function setSportId($sport_id)
    {
        $this->sport_id = $sport_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getSportTitle()
    {
        return $this->sport_title;
    }

    /**
     * @param string $sport_title
     * @return $this
     */
    public function setSportTitle($sport_title)
    {
        $this->sport_title = $sport_title;
        return $this;
    }

    public function getProblemType()
    {
        return SportEventProblem::PROBLEM_SPORT_ID;
    }

    public function checkFieldList()
    {
        return ['sport_id', 'sport_title'];
    }

    public function hasProblem()
    {
        $r = $this->getSportEvent()->getSportId() != $this->getSportId();
        return $r;
    }

    public function checkSameProblem($problems)
    {
        if(!is_array($problems))
            $problems = [$problems];

        /** @var SportEventProblem $problem */
        foreach ($problems as $problem) {
            $custom = unserialize($problem->getCustom());
            $arr1 = [
                $custom['set_sport_id'],
                $custom['get_sport_id'],
            ];

            $arr2 = [
                $this->getSportEvent()->getSportId(),
                $this->getSportId()
            ];
            if($arr1 == $arr2)
                return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function checkProblem()
    {
        $isProblem = $this->hasProblem();

        $this->setIsProblem($isProblem);
        if($isProblem && !$this->getProblemEvent()) {
            $msg = sprintf('Сменилась лига на %s', $this->getSportTitle());
            $params = [
                'set_sport_id' => $this->getSportEvent()->getSportId(),
                'get_sport_id' => $this->getSportId(),
            ];
            return $this
                ->setIsProblem(false)
                ->create($msg, $params);
        } elseif(!$isProblem && $this->getProblemEvent()) {
            return $this
                ->setIsProblem(false)
                ->resolve();
        }

        return true;
    }
}