<?php
namespace common\factories\problem_event\_default\_interface;

/**
 * Created by PhpStorm.
 */

interface iProblemEvent
{
    public function checkFieldList();

    /**
     * @return string
     */
    public function getProblemType();

    /**
     * @param \SportEvent $event
     * @return $this
     */
    public function setSportEvent(&$event);

    /**
     * @return \SportEvent
     */
    public function getSportEvent();

    /**
     * @param \SportEventProblem $problem
     * @return $this
     */
    public function setProblemEvent(&$problem);

    /**
     * @return \SportEventProblem
     */
    public function getProblemEvent();

    /**
     * @param null $resolver_id
     * @param array $params
     * @return boolean
     */
    public function resolve($resolver_id = null, $params = []);

    /**
     * @param $desc
     * @param $params
     * @return boolean
     */
    public function create($desc, $params);

    /**
     * @return boolean
     */
    public function isProblem();

    /**
     * @return boolean
     */
    public function isNew();

    /**
     * @return boolean
     */
    public function hasProblem();

    /**
     * @return boolean
     */
    public function checkProblem();

    /**
     * @param $problems
     * @return boolean
     */
    public function checkSameProblem($problems);
}