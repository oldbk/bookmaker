<?php
namespace common\helpers;
use common\factories\parser\results\_interfaces\iResult;
use common\factories\problem_event\_default\_interface\iProblemEvent;
use common\factories\ProblemEventFactory;
use common\helpers\fixed\Value as FixedValue;
use iSportEvent;
use SportEventProblem;
/**
 * Class ProblemEventChecker
 * @package common\helpers
 *
 * @method boolean all()
 * @method boolean check($problem_types)
 */
class ProblemEventChecker
{

    /** @var iSportEvent */
    protected $Event;
    /** @var array */
    protected $new_event = [];

    /** @var SportEventProblem[] */
    protected $problems = [];

    /** @var SportEventProblem[] */
    protected $problems_resolved = [];

    /** @var FixedValue */
    protected $EventFixedValue;

    /** @var bool */
    protected $is_check_db_problem = false;

    /** @var null */
    protected $data = null;

    /** @var int */
    protected $problem_count = 0;

    /** @var bool */
    protected $have_any_problem = false;

    /** @var int */
    protected $sport_id;

    /** @var null|string */
    protected $sport_title = null;

    protected $result_event;

    /**
     * @param iSportEvent $Event
     */
    public function __construct(&$Event)
    {
        $this->setEvent($Event);
    }

    protected function beforeAction()
    {
        if(!$this->getEvent())
            throw new \Exception('Событие не задано');

        if(!$this->is_check_db_problem) {
            $this->is_check_db_problem = true;

            $criteria = new \CDbCriteria();
            $criteria->addCondition('event_id = :event_id');
            //$criteria->scopes = ['notResolve'];
            $criteria->params = [
                ':event_id' => $this->getEvent()->getId(),
            ];

            $problems_resolved = $problems_not_resolved = [];

            /** @var SportEventProblem[] $models */
            $models = SportEventProblem::model()->findAll($criteria);
            foreach ($models as $model) {
                if($model->getIsResolved())
                    $problems_resolved[] = $model;
                else
                    $problems_not_resolved[] = $model;
            }

            $this->setProblems($problems_not_resolved)
                ->setProblemCount(count($problems_not_resolved))
                ->setProblemsResolved($problems_resolved);
        }
    }

    /**
     * @return iSportEvent
     */
    public function getEvent()
    {
        return $this->Event;
    }

    /**
     * @param iSportEvent $Event
     * @return $this
     */
    public function setEvent(&$Event)
    {
        $this->Event = $Event;
        return $this;
    }

    /**
     * @return array
     */
    public function getNewEvent()
    {
        return $this->new_event;
    }

    /**
     * @param array $new_event
     * @return $this
     */
    public function setNewEvent($new_event)
    {
        $this->new_event = $new_event;
        return $this;
    }

    /**
     * @return \SportEventProblem[]
     */
    public function getProblems()
    {
        return $this->problems;
    }

    /**
     * @param \SportEventProblem[] $problems
     * @return $this
     */
    public function setProblems($problems)
    {
        $this->problems = $problems;
        return $this;
    }

    /**
     * @param \SportEventProblem $problem
     */
    public function addProblem($problem)
    {
        $this->problems[] = $problem;
    }

    /**
     * @return FixedValue
     */
    public function getEventFixedValue()
    {
        return $this->EventFixedValue;
    }

    /**
     * @param FixedValue $EventFixedValue
     * @return $this
     */
    public function setEventFixedValue($EventFixedValue)
    {
        $this->EventFixedValue = $EventFixedValue;
        return $this;
    }

    /**
     * @return null
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param null $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return int
     */
    public function getProblemCount()
    {
        return $this->problem_count;
    }

    /**
     * @param int $problem_count
     * @return $this
     */
    public function setProblemCount($problem_count)
    {
        $this->problem_count = $problem_count;
        return $this;
    }

    /**
     * @param null $param
     * @return array
     */
    public function getAdditionalProblemParams($param = null)
    {
        if($param === null)
            return $this->_additionalProblemParams;

        return isset($this->_additionalProblemParams[$param]) ? $this->_additionalProblemParams[$param] : [];
    }

    /**
     * @param array $additionalProblemParams
     * @return $this
     */
    public function setAdditionalProblemParams($additionalProblemParams)
    {
        $this->_additionalProblemParams = $additionalProblemParams;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isHaveAnyProblem()
    {
        return $this->have_any_problem;
    }

    /**
     * @param boolean $have_any_problem
     * @return $this
     */
    public function setHaveAnyProblem($have_any_problem)
    {
        $this->have_any_problem = $have_any_problem;
        return $this;
    }

    public function isHaveProblem($problem_type)
    {
        foreach ($this->getProblems() as $problem) {
            if($problem->getProblemType() == $problem_type && !$problem->getIsResolved())
                return true;
        }

        return false;
    }

    /**
     * @return \SportEventProblem[]
     */
    public function getProblemsResolved()
    {
        return $this->problems_resolved;
    }

    /**
     * @param \SportEventProblem[] $problems_resolved
     * @return $this
     */
    public function setProblemsResolved($problems_resolved)
    {
        $this->problems_resolved = $problems_resolved;
        return $this;
    }

    /**
     * @param $problem_type
     * @return bool|SportEventProblem
     */
    protected function getCurrentProblem($problem_type)
    {
        foreach ($this->getProblems() as $model) {
            if($model->getProblemType() == $problem_type)
                return $model;
        }

        return null;
    }

    protected function getResolvedProblemsByType($problem_type)
    {
        $returned = [];
        foreach ($this->getProblemsResolved() as $model) {
            if($model->getProblemType() == $problem_type)
                $returned[] = $model;
        }

        return $returned;
    }

    private $_additionalProblemParams = [
        SportEventProblem::PROBLEM_DATE => [
            'FixedValue' => 'getEventFixedValue',
            'new_event_data' => 'getNewEvent'
        ],
        SportEventProblem::PROBLEM_SPORT_ID => [
            'sport_id' => 'getSportId',
            'sport_title' => 'getSportTitle',
        ]
    ];

    /**
     * @return bool
     * @throws \common\components\NException
     */
    public function allAction()
    {
        $this->checkAction(SportEventProblem::getProblemList());
    }

    public function checkAction($problem_types = [])
    {
        $problem_count = 0;
        if(!is_array($problem_types))
            $problem_types = [$problem_types];

        foreach ($problem_types as $problem_type) {
            $ProblemEventFactory = $this->getFactory($problem_type);
            if($ProblemEventFactory->hasProblem() && $ProblemEventFactory->checkSameProblem($this->getResolvedProblemsByType($problem_type)))
                continue;

            $ProblemEventFactory->checkProblem();
            if($ProblemEventFactory->isNew())
                $this->addProblem($ProblemEventFactory->getProblemEvent());
            if($ProblemEventFactory->isProblem())
                $this->_checkerFields = \CMap::mergeArray($this->_checkerFields, $ProblemEventFactory->checkFieldList());
        }

        foreach ($this->getProblems() as $problem) {
            if(!$problem->getIsResolved())
                $problem_count++;
        }

        $this
            ->setHaveAnyProblem($problem_count > 0 ? true : false)
            ->setProblemCount($problem_count);

        return true;
    }

    private $_checkerFields = [];

    /**
     * @param $problem_type
     * @return iProblemEvent
     * @throws \common\components\NException
     */
    protected function getFactory($problem_type)
    {
        $params = [
            'SportEvent' => $this->getEvent(),
            'Problem' => $this->getCurrentProblem($problem_type),
        ];
        foreach ($this->getAdditionalProblemParams($problem_type) as $arg => $methodCall) {
            if(method_exists($this, $methodCall))
                $params[$arg] = call_user_func([$this, $methodCall]);
        }
        $factory = ProblemEventFactory::factory($this->getEvent()->getSportType(), $problem_type, $params);

        return $factory;
    }

    public function __call($name, $arguments)
    {
        $method = sprintf('%sAction', $name);
        if(method_exists($this, $method)) {
            $this->beforeAction();
            call_user_func_array([$this, $method], $arguments);
        } else
            throw new \BadMethodCallException(sprintf('Method not exists: %s. ProblemEventChecker', $name));
    }

    /**
     * @return array
     */
    public function getCheckerFields()
    {
        return $this->_checkerFields;
    }

    /**
     * @param array $checkerFields
     * @return $this
     */
    public function setCheckerFields($checkerFields)
    {
        $this->_checkerFields = $checkerFields;
        return $this;
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
     * @return null|string
     */
    public function getSportTitle()
    {
        return $this->sport_title;
    }

    /**
     * @param null|string $sport_title
     * @return $this
     */
    public function setSportTitle($sport_title)
    {
        $this->sport_title = $sport_title;
        return $this;
    }

    /**
     * @return iResult
     */
    public function getResultEvent()
    {
        return $this->result_event;
    }

    /**
     * @param mixed $result_event
     * @return $this
     */
    public function setResultEvent($result_event)
    {
        $this->result_event = $result_event;
        return $this;
    }
}