<?php
namespace common\factories\problem_event\_default;

use common\factories\problem_event\_default\_base\BaseEventProblem;
use common\factories\problem_event\_default\_interface\iProblemEvent;
use common\helpers\fixed\Value as FixedValue;
use SportEventProblem;

/**
 * Created by PhpStorm.
 */
class BaseDate extends BaseEventProblem implements iProblemEvent
{
    /** @var array */
    protected $new_event_data = [];

    /** @var FixedValue */
    protected $fixedValue;

    /**
     * @param $SportEvent
     * @param null|SportEventProblem $Problem
     * @param $FixedValue
     * @param $new_event_data
     */
    public function __construct(&$SportEvent, &$Problem, $FixedValue = null, $new_event_data = [])
    {
        parent::__construct($SportEvent, $Problem);

        $this->setFixedValue($FixedValue)
            ->setNewEventData($new_event_data);
    }

    public function checkFieldList()
    {
        return ['data_int', 'date_string'];
    }

    public function getProblemType()
    {
        return SportEventProblem::PROBLEM_DATE;
    }

    /**
     * @return FixedValue
     */
    public function getFixedValue()
    {
        return $this->fixedValue;
    }

    /**
     * @param FixedValue
     * @return $this
     */
    public function setFixedValue($fixedValue)
    {
        $this->fixedValue = $fixedValue;
        return $this;
    }

    /**
     * @return array
     */
    public function getNewEventData()
    {
        return $this->new_event_data;
    }

    /**
     * @param array $new_event_data
     * @return $this
     */
    public function setNewEventData($new_event_data)
    {
        $this->new_event_data = $new_event_data;
        return $this;
    }

    public function hasProblem()
    {
        $attributes = $this->getSportEvent()->getOldAttributes();
        if(!isset($attributes['date_int']) || $this->getFixedValue()->date_int !== null)
            return false;

        return $attributes['date_int'] != $this->getSportEvent()->getDateInt();
    }

    /**
     * @param $problems
     * @return bool
     */
    public function checkSameProblem($problems)
    {
        $attributes = $this->getSportEvent()->getOldAttributes();
        if(!is_array($problems))
            $problems = [$problems];

        /** @var SportEventProblem $problem */
        foreach ($problems as $problem) {
            $custom = unserialize($problem->getCustom());
            if($custom['come_date_int'] == $this->getSportEvent()->getDateInt()
                && $custom['date_int'] == $attributes['date_int'])

                return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function checkProblem()
    {
        $attributes = $this->getSportEvent()->getOldAttributes();
        $isProblem = $this->hasProblem();

        $this->setIsProblem($isProblem);
        if($isProblem && !$this->getProblemEvent()) {
            $msg = sprintf('Некорректная дата. Ранее была %s. Пришла %s',
                date('d.m.Y H:i:s', $attributes['date_int']), date('d.m.Y H:i:s', $this->getSportEvent()->getDateInt()));
            $params = [
                'come_date_int' => $this->getSportEvent()->getDateInt(),
                'date_int' => $attributes['date_int']
            ];
            return $this
                ->setIsProblem(true)
                ->create($msg, $params);
        } elseif(!$isProblem && $this->getProblemEvent()) {
            return $this
                ->setIsProblem(false)
                ->resolve();
        }

        return true;
    }
}