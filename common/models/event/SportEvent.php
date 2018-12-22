<?php

Yii::import('common.models._base.BaseSportEvent');
use \common\interfaces\iStatus;
use \common\helpers\SportHelper;
use \common\singletons\prices\PricesEKR;
use \common\singletons\prices\PricesKR;
/**
 * Class SportEvent
 *
 * Relations
 * @property SportEventFixedValue[] ratioFixed
 * @property UserBetting[] checkUserBetting
 * @property int $betCount
 *
 * @property boolean $is_freeze
 * @property int $team_1_id
 * @property int $team_2_id
 * @property boolean $is_trash
 * @property boolean $have_problem
 * @property int $problem_count
 * @property string $admin_text
 * @property float $ratio_change_max_price
 * @property int $sport_type
 * @property string $event_template
 * @property boolean not_auto
 * @property string not_auto_reason
 *
 * @method SportEvent feature()
 *
 * @property object onAfterEventAccept
 * @property object onAfterEventRatioChange
 * @property object onAfterEventClose
 * @property object onAfterEventDecline
 * @property object onAfterEventRefund
 * @property object onAfterEventTrash
 * @property object onAfterTrashRecovery
 * @property object onAfterEventChange
 */
class SportEvent extends BaseSportEvent implements \common\interfaces\iAdminLog
{
	use \common\traits\tEvent;

	const TYPE_EVENT_WINNER = 0;
	const TYPE_EVENT_FULL   = 1;

	public $cnt = 0;
    public $ratio_type = null;
	public $status = iStatus::STATUS_NEW;
	protected $new_ratio;
	protected $old_ratio;
	protected $result;

	/**
	 * @param string $className
	 * @return SportEvent
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	/**
	 * @return iSportEvent
	 */
	public function copy()
	{
		$Event = clone $this;
		$Event
			->setNewRatio(clone $this->getNewRatio())
			->setOldRatio(clone $this->getOldRatio())
			->setResult(clone $this->getResult());

		return $Event;
	}

	/**
	 * @param array $attributes
	 * @return FootballEvent
	 * @throws Exception
	 */
	protected function instantiate($attributes)
	{
		return self::getInstance($attributes['sport_type'], null);
	}

	public static function getInstance($sport_type, $scenario = 'insert')
	{
		switch ($sport_type) {
			case SportHelper::SPORT_FOOTBALL_ID:
				return new FootballEvent($scenario);
				break;
			case SportHelper::SPORT_TENNIS_ID:
				return new TennisEvent($scenario);
				break;
			case SportHelper::SPORT_BASKETBALL_ID:
				return new BasketballEvent($scenario);
				break;
			case SportHelper::SPORT_HOKKEY_ID:
				return new HokkeyEvent($scenario);
				break;
		}

		throw new Exception('Вид спорта не найден', 420);
	}

	public function behaviors()
	{
		return [
			// Password behavior strategy
			'MTimestampBehavior' => [
				'class' => 'common\extensions\behaviors\MTimestampBehavior',
				'createAttribute' => 'create_at',
				'updateAttribute' => 'update_at',
				'setUpdateOnCreate' => true
			]
		];
	}

    public function scopes()
    {
        $t = $this->getTableAlias(false, false);
        return [
            'enable' => [
                'condition' => "`{$t}`.status = :{$t}_enable",
                'params' => [":{$t}_enable" => iStatus::STATUS_ENABLE]
            ],
            'trash' => [
                'condition' => "`{$t}`.is_trash = 1",
            ],
            'nTrash' => [
                'condition' => "`{$t}`.is_trash = 0",
            ],
            'feature' => [
                'condition' => "`{$t}`.date_int > :{$t}_date_int",
                'params' => [":{$t}_date_int" => time()]
            ],
            'haveProblem' => [
                'condition' => "`{$t}`.have_problem = 1"
            ],
            'nHaveProblem' => [
                'condition' => "`{$t}`.have_problem = 0"
            ],
            'for_user' => [
                'condition' => "`{$t}`.status = :{$t}_enable and `{$t}`.is_trash = 0 and `{$t}`.date_int > :{$t}_date_int and `{$t}`.have_problem = 0",
                'params' => [
                    ":{$t}_enable" => iStatus::STATUS_ENABLE,
                    ":{$t}_date_int" => time()
                ]
            ],
			'have_result' => [
				'condition' => "`{$t}`.have_result = 1"
			]
        ];
    }

	public function afterFind()
	{
		parent::afterFind();

		foreach ($this->getCompareList() as $field)
			$this->_old_attributes[$field] = $this->getAttribute($field);
	}

    public function attributeLabels() {
        return [
            'date_int' => Yii::t('app', 'Начало события'),
            'create_at' => Yii::t('app', 'Дата создания'),
        ];
    }

	public function relations() {
		return [
			'sport' => [
				self::BELONGS_TO,
				'Sport',
				'sport_id',
				'joinType' => 'inner join'
			],
			'hasResult' => [
				self::HAS_ONE,
				'SportEventResult',
				'event_id',
				'joinType' => 'left join',
				'on' => '`hasResult`.result_field = "is_cancel" and `hasResult`.value = 0',
				'condition' => '`hasResult`.event_id is not NULL',
			],
            'checkUserBetting' => [
                self::HAS_MANY,
                'UserBetting',
                'event_id',
                'joinType' => 'left join'
            ],
            'ratioFixed' => [
                self::HAS_MANY,
                'SportEventFixedValue',
                'event_id',
                'joinType' => 'left join',
            ],
            'hasResolvedProblem' => [
                self::HAS_ONE,
                'SportEventProblem',
                'event_id',
                'joinType' => 'left join',
                'on' => '`hasResolvedProblem`.is_resolved = 1',
                'condition' => '`hasResolvedProblem`.id is not NULL',
                'select' => []
            ],
			'betCount' => [
				self::STAT,
				'UserBetting',
				'event_id',
			],
		];
	}

    public function rules() {
        return [
            ['sport_id, sport_title, number, date_int, date_string, status', 'required', 'on' => 'insert, update'],
            ['sport_id, date_int, update_at, create_at, status, _v, is_new, have_result, is_freeze', 'numerical', 'integerOnly' => true],
            ['sport_title, number, date_string, team_1, team_2', 'length', 'max'=>255],
            ['team_1, team_2, update_at, create_at, _v, is_new, have_result, is_freeze, admin_text', 'default', 'setOnEmpty' => true, 'value' => null],
            ['id, sport_id, sport_title, number, date_int, date_string, team_1, team_2, update_at, create_at, status, _v, is_new, have_result, is_freeze, admin_text', 'safe', 'on' => 'search'],
            ['is_freeze', 'checkFreeze', 'on' => 'betting'],
        ];
    }

	public function checkFreeze()
	{
		if($this->isFreeze())
			$this->addError('status', 'Событие временно недоступно для ставок. '.$this->getTitle());
	}

	public function getTitle()
	{
		return $this->sport->getTitle().'. '.$this->getTeam1().' - '.$this->getTeam2();
	}

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param int $id
	 * @return $this
	 */
	public function setId($id)
	{
		$this->id = $id;
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

	/**
	 * @return string
	 */
	public function getNumber()
	{
		return $this->number;
	}

	/**
	 * @param string $number
	 * @return $this
	 */
	public function setNumber($number)
	{
		$this->number = $number;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getDateInt()
	{
		return $this->date_int;
	}

	/**
	 * @param int $date_int
	 * @return $this
	 */
	public function setDateInt($date_int)
	{
		$this->date_int = $date_int;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getDateString()
	{
		return $this->date_string;
	}

	/**
	 * @param string $date_string
	 * @return $this
	 */
	public function setDateString($date_string)
	{
		$this->date_string = $date_string;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getTeam1()
	{
		return $this->team_1;
	}

	/**
	 * @param string $team_1
	 * @return $this
	 */
	public function setTeam1($team_1)
	{
		$this->team_1 = $team_1;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getTeam2()
	{
		return $this->team_2;
	}

	/**
	 * @param string $team_2
	 * @return $this
	 */
	public function setTeam2($team_2)
	{
		$this->team_2 = $team_2;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getUpdateAt()
	{
		return $this->update_at;
	}

	/**
	 * @param int $update_at
	 * @return $this
	 */
	public function setUpdateAt($update_at)
	{
		$this->update_at = $update_at;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getCreateAt()
	{
		return $this->create_at;
	}

	/**
	 * @param int $create_at
	 * @return $this
	 */
	public function setCreateAt($create_at)
	{
		$this->create_at = $create_at;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getStatus()
	{
		return $this->status;
	}

	/**
	 * @param int $status
	 * @return $this
	 */
	public function setStatus($status)
	{
		$this->status = $status;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getV()
	{
		return $this->_v;
	}

	/**
	 * @param int $v
	 * @return $this
	 */
	public function setV($v)
	{
		$this->_v = $v;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getIsNew()
	{
		return $this->is_new;
	}

	/**
	 * @param int $is_new
	 * @return $this
	 */
	public function setIsNew($is_new)
	{
		$this->is_new = $is_new;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getHaveResult()
	{
		return $this->have_result;
	}

	/**
	 * @param int $have_result
	 * @return $this
	 */
	public function setHaveResult($have_result)
	{
		$this->have_result = $have_result;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function isFreeze()
	{
		return $this->is_freeze;
	}

	/**
	 * @param boolean $is_freeze
	 * @return $this
	 */
	public function setIsFreeze($is_freeze)
	{
		$this->is_freeze = $is_freeze;
		return $this;
	}

    /**
     * @return int
     */
    public function getTeam1Id()
    {
        return $this->team_1_id;
    }

    /**
     * @param int $team_1_id
     * @return $this
     */
    public function setTeam1Id($team_1_id)
    {
        $this->team_1_id = $team_1_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getTeam2Id()
    {
        return $this->team_2_id;
    }

    /**
     * @param int $team_2_id
     * @return $this
     */
    public function setTeam2Id($team_2_id)
    {
        $this->team_2_id = $team_2_id;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isTrash()
    {
        return $this->is_trash;
    }

    /**
     * @param boolean $is_trash
     * @return $this
     */
    public function setIsTrash($is_trash)
    {
        $this->is_trash = $is_trash;
        return $this;
    }

    /**
     * @return string
     */
    public function getAdminText()
    {
        return $this->admin_text;
    }

    /**
     * @param string $admin_text
     * @return $this
     */
    public function setAdminText($admin_text)
    {
        $this->admin_text = $admin_text;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isHaveProblem()
    {
        return $this->have_problem;
    }

    /**
     * @param boolean $have_problem
     * @return $this
     */
    public function setHaveProblem($have_problem)
    {
        $this->have_problem = $have_problem;
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
	 * @return float
	 */
	public function getRatioChangeMaxPrice()
	{
		return $this->ratio_change_max_price;
	}

	/**
	 * @param float $ratio_change_max_price
	 * @return $this
	 */
	public function setRatioChangeMaxPrice($ratio_change_max_price)
	{
		$this->ratio_change_max_price = $ratio_change_max_price;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getSportType()
	{
		return $this->sport_type;
	}

	/**
	 * @param int $sport_type
	 * @return $this
	 */
	public function setSportType($sport_type)
	{
		$this->sport_type = $sport_type;
		return $this;
	}

	/**
	 * @return \common\sport\ratio\_interfaces\iRatio
	 */
	public function getNewRatio()
	{
		return $this->new_ratio;
	}

	/**
	 * @param mixed $new_ratio
	 * @return $this
	 */
	public function setNewRatio($new_ratio)
	{
		$this->new_ratio = $new_ratio;
		return $this;
	}

	/**
	 * @return \common\sport\ratio\_interfaces\iRatio
	 */
	public function getOldRatio()
	{
		return $this->old_ratio;
	}

	/**
	 * @param mixed $old_ratio
	 * @return $this
	 */
	public function setOldRatio($old_ratio)
	{
		$this->old_ratio = $old_ratio;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getEventTemplate()
	{
		return $this->event_template;
	}

	/**
	 * @param string $event_template
	 * @return $this
	 */
	public function setEventTemplate($event_template)
	{
		$this->event_template = $event_template;
		return $this;
	}

	/**
	 * @return SportEventFixedValue[]
	 */
	public function getRatioFixed()
	{
		return $this->ratioFixed;
	}

	/**
	 * @param SportEventFixedValue[] $ratioFixed
	 * @return $this
	 */
	public function setRatioFixed($ratioFixed)
	{
		$this->ratioFixed = $ratioFixed;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function isNotAuto()
	{
		return $this->not_auto;
	}

	/**
	 * @param boolean $not_auto
	 *
	 * @return $this
	 */
	public function setNotAuto($not_auto)
	{
		$this->not_auto = $not_auto;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getNotAutoReason()
	{
		return $this->not_auto_reason;
	}

	/**
	 * @param string $not_auto_reason
	 *
	 * @return $this
	 */
	public function setNotAutoReason($not_auto_reason)
	{
		$this->not_auto_reason = $not_auto_reason;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getBetCount()
	{
		return $this->betCount;
	}

	public function getCnt()
	{
		return $this->cnt;
	}

	/**
	 * @param int $betCount
	 * @return $this
	 */
	public function setBetCount($betCount)
	{
		$this->betCount = $betCount;
		return $this;
	}

    private $_events = [
        'onAfterEventAccept', 'onAfterEventClose', 'onAfterEventDecline',
        'onAfterEventRefund', 'onAfterEventTrash', 'onAfterTrashRecovery',
        'onAfterEventChange', 'onAfterEventRatioChange'
    ];
    public function onAfterEventAccept($event)
    {
        $this->raiseEvent('onAfterEventAccept', $event);
    }
    public function onAfterEventRatioChange($event)
    {
        $this->raiseEvent('onAfterEventRatioChange', $event);
    }
    public function onAfterEventClose($event)
    {
        $this->raiseEvent('onAfterEventClose', $event);
    }
    public function onAfterEventDecline($event)
    {
        $this->raiseEvent('onAfterEventDecline', $event);
    }
    public function onAfterEventRefund($event)
    {
        $this->raiseEvent('onAfterEventRefund', $event);
    }
    public function onAfterEventTrash($event)
    {
        $this->raiseEvent('onAfterEventTrash', $event);
    }
    public function onAfterTrashRecovery($event)
    {
        $this->raiseEvent('onAfterTrashRecovery', $event);
    }
    public function onAfterEventChange($event)
    {
        $this->raiseEvent('onAfterEventChange', $event);
    }

    public function updateAction()
    {
        $r = $this->save();

        if($r) {
            foreach ($this->_events as $_event) {
                if($this->hasEvent($_event))
                    $this->{$_event}(new \CEvent($this));
            }
        }

        return $r;
    }

	private $_old_attributes = [];
	public function getOldAttributes()
	{
		return $this->_old_attributes;
	}

	public function getNewAttributes()
	{
		$return = [];
		foreach ($this->getCompareList() as $field)
			$return[$field] = $this->getAttribute($field);

		return $return;
	}

	public function getCompareList()
	{
		return [
			'number',
			'team_1',
			'team_2',
			'date_int',
			'admin_text'
		];
	}

	public function getEventTypeView()
	{
		return \common\helpers\SportHelper::getByID($this->getSportType());
	}

	/**
	 * @return \common\sport\result\iResult
	 */
	public function getResult()
	{
		return $this->result;
	}

	/**
	 * @param mixed $result
	 * @return $this
	 */
	public function setResult($result)
	{
		$this->result = $result;
		return $this;
	}

	public function getSport()
	{
		return $this->sport;
	}

	public function canAuto()
	{
		if($this->isHaveProblem()) {
			$this->setNotAuto(true)
				->setNotAutoReason('Имеется проблема');

			return false;
		}

		if($this->getDateInt() >= strtotime('+5 days'))
			return false;

		if(!$this->getNewRatio()->canAuto()) {
			$this->setNotAuto(true)
				->setNotAutoReason($this->getNewRatio()->getNotAutoReason());

			return false;
		}

		if(preg_match('/^\d\w+/ui', $this->getTeam1()) || preg_match('/^\d\w+/ui', $this->getTeam2())) {
			$this->setNotAuto(true)
					->setNotAutoReason('Команда начинается с цифры и слитно с тектом');

			return false;
		}

		if(!$this->getIsNewRecord() && $this->getStatus() != iStatus::STATUS_NEW) {
			$this->setNotAuto(true)
					->setNotAutoReason('Запись не является новой и не может быть включено автоматически');

			return false;
		}

		return true;
	}

	public function getMapping()
	{
		return [];
	}

	/**
	 * @return array
	 */
	public function prepareForSocket()
	{
		$return = [];

		$prices = [
			PricesEKR::init()->getId() => PricesEKR::init()->getDopRatio(),
			PricesKR::init()->getId() => PricesKR::init()->getDopRatio(),
		];
		foreach ($this->getMapping() as $field => $alias) {
			foreach ($prices as $id => $dop_ratio)
				$return[$id][$alias] = Yii::app()->getSport()->prepareRatio($this->getNewRatio()->getAttribute($field), null, $dop_ratio);
		}

		return $return;
	}
}