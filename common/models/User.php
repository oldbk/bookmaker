<?php

Yii::import('common.models._base.BaseUser');

/**
 * Class User
 *
 * @property UserBank[] $userBanks
 * @property UserActiveBalance[] $userActiveBalances
 *
 * @property boolean is_blocked
 * @property string blocked_message
 *
 * @property object onAfterChange
 */
class User extends BaseUser implements \common\interfaces\iPrice
{
	public $_input_output = 0;
    public $sum_kr = 0;
    public $sum_ekr = 0;
    public $sum_gold = 0;

	/**
	 * @param string $className
	 * @return BaseModel
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function afterFind()
	{
		parent::afterFind();

		foreach ($this->getCompareList() as $field)
			$this->_old_attributes[$field] = $this->getAttribute($field);
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
        return ['extra_ratio', 'admin_comment'];
    }

	public function attributeLabels() {
		return [
			'id' => Yii::t('app', 'ID'),
			'game_id' => Yii::t('app', 'Game'),
			'login' => Yii::t('app', 'Логин'),
			'kr_balance' => Yii::t('app', 'Kr Balance'),
			'ekr_balance' => Yii::t('app', 'Ekr Balance'),
			'voucher_balance' => Yii::t('app', 'Voucher Balance'),
			'gold_balance' => Yii::t('app', 'Gold Balance'),
			'update_at' => Yii::t('app', 'Update At'),
			'create_at' => Yii::t('app', 'Create At'),
			'extra_ratio' => Yii::t('app', 'Доп. коэфф.'),
			'admin_comment' => Yii::t('app', 'Заметки админа'),
			'is_blocked' => Yii::t('app', 'Заблокирован?'),
		];
	}


    public function rules() {
        return [
            ['game_id, login', 'required'],
            ['game_id, level, update_at, create_at, active_balance', 'numerical', 'integerOnly'=>true],
            ['align, klan, login', 'length', 'max'=>255],
            ['kr_balance, ekr_balance, voucher_balance, gold_balance, extra_ratio', 'length', 'max'=>19],
            ['admin_comment, is_blocked, blocked_message', 'safe'],
            ['align, klan, level, kr_balance, ekr_balance, voucher_balance, gold_balance, update_at, create_at, active_balance, extra_ratio, admin_comment, is_blocked, blocked_message', 'default', 'setOnEmpty' => true, 'value' => null],
            ['id, game_id, align, klan, login, level, kr_balance, ekr_balance, voucher_balance, gold_balance, update_at, create_at, active_balance, extra_ratio, admin_comment', 'safe', 'on'=>'search'],
            ['login', 'unsafe', 'on' => 'updateAction']
        ];
    }

    public function relations() {
        return [
            'userActiveBalances' => [
                self::HAS_MANY,
                'UserActiveBalance',
                'user_id'
            ],
			'userActiveBalanceKR' => [
				self::HAS_ONE,
				'UserActiveBalance',
				'user_id',
				'on' => 'userActiveBalanceKR.price_type = :userActiveBalanceKR_price_type',
				'params' => [
					':userActiveBalanceKR_price_type' => \common\interfaces\iPrice::TYPE_KR,
				]
			],
			'userActiveBalanceEKR' => [
				self::HAS_ONE,
				'UserActiveBalance',
				'user_id',
				'on' => 'userActiveBalanceEKR.price_type = :userActiveBalanceEKR_price_type',
				'params' => [
					':userActiveBalanceEKR_price_type' => \common\interfaces\iPrice::TYPE_EKR,
				]
			],
			'userActiveBalanceGold' => [
				self::HAS_ONE,
				'UserActiveBalance',
				'user_id',
				'on' => 'userActiveBalanceGold.price_type = :userActiveBalanceGold_price_type',
				'params' => [
					':userActiveBalanceGold_price_type' => \common\interfaces\iPrice::TYPE_GOLD,
				]
			],
            'userBanks' => [
                self::HAS_MANY,
                'UserBank',
                'user_id'
            ],
        ];
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
	public function getGameId()
	{
		return $this->game_id;
	}

	/**
	 * @param int $game_id
	 * @return $this
	 */
	public function setGameId($game_id)
	{
		$this->game_id = $game_id;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getLogin()
	{
		return $this->login;
	}

	/**
	 * @param string $login
	 * @return $this
	 */
	public function setLogin($login)
	{
		$this->login = $login;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getKrBalance()
	{
		return $this->kr_balance;
	}

	/**
	 * @param string $kr_balance
	 * @return $this
	 */
	public function setKrBalance($kr_balance)
	{
		$this->kr_balance = $kr_balance;
		return $this;
	}

    public function addKrBalance($price)
    {
        $sum = ($this->kr_balance * 100 + $price * 100)/100;
        $this->kr_balance = \common\helpers\Convert::getMoneyFormat($sum);
        return $this;
    }

    /**
     * @param $price
     * @return $this
     */
    public function takeKrBalance($price)
    {
        $diff = ($this->kr_balance * 100 - $price * 100)/100;
        $this->kr_balance = \common\helpers\Convert::getMoneyFormat($diff);
        return $this;
    }

	/**
	 * @return string
	 */
	public function getEkrBalance()
	{
		return $this->ekr_balance;
	}

	/**
	 * @param string $ekr_balance
	 * @return $this
	 */
	public function setEkrBalance($ekr_balance)
	{
		$this->ekr_balance = $ekr_balance;
		return $this;
	}

    public function addEkrBalance($price)
    {
        $sum = ($this->ekr_balance * 100 + $price * 100)/100;
        $this->ekr_balance = \common\helpers\Convert::getMoneyFormat($sum);
        return $this;
    }

    public function takeEkrBalance($price)
    {
        $diff = ($this->ekr_balance * 100 - $price * 100)/100;
        $this->ekr_balance = \common\helpers\Convert::getMoneyFormat($diff);
        return $this;
    }

	/**
	 * @return string
	 */
	public function getGoldBalance()
	{
		return $this->gold_balance;
	}

	/**
	 * @param string $gold_balance
	 * @return $this
	 */
	public function setGoldBalance($gold_balance)
	{
		$this->gold_balance = $gold_balance;
		return $this;
	}

	public function addGoldBalance($price)
	{
		$sum = ($this->gold_balance * 100 + $price * 100)/100;
		$this->gold_balance = \common\helpers\Convert::getMoneyFormat($sum);
		return $this;
	}

	public function takeGoldBalance($price)
	{
		$diff = ($this->gold_balance * 100 - $price * 100)/100;
		$this->gold_balance = \common\helpers\Convert::getMoneyFormat($diff);
		return $this;
	}


	/**
	 * @return string
	 */
	public function getVoucherBalance()
	{
		return $this->voucher_balance;
	}

	/**
	 * @param string $voucher_balance
	 * @return $this
	 */
	public function setVoucherBalance($voucher_balance)
	{
		$this->voucher_balance = $voucher_balance;
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
	public function getActiveBalance()
	{
		return $this->active_balance;
	}

	/**
	 * @param int $active_balance
	 * @return $this
	 */
	public function setActiveBalance($active_balance)
	{
		$this->active_balance = $active_balance;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getAlign()
	{
		return $this->align;
	}

	/**
	 * @param string $align
	 * @return $this
	 */
	public function setAlign($align)
	{
		$this->align = $align;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getKlan()
	{
		return $this->klan;
	}

	/**
	 * @param string $klan
	 * @return $this
	 */
	public function setKlan($klan)
	{
		$this->klan = $klan;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getLevel()
	{
		return $this->level;
	}

	/**
	 * @param int $level
	 * @return $this
	 */
	public function setLevel($level)
	{
		$this->level = $level;
		return $this;
	}

	/**
	 * @return float
	 */
	public function getExtraRatio()
	{
		return $this->extra_ratio;
	}

	/**
	 * @param float $extra_ratio
	 * @return $this
	 */
	public function setExtraRatio($extra_ratio)
	{
		$this->extra_ratio = $extra_ratio;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getAdminComment()
	{
		return $this->admin_comment;
	}

	/**
	 * @param string $admin_comment
	 * @return $this
	 */
	public function setAdminComment($admin_comment)
	{
		$this->admin_comment = $admin_comment;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getBlockedMessage()
	{
		return $this->blocked_message;
	}

	/**
	 * @param string $blocked_message
	 * @return $this
	 */
	public function setBlockedMessage($blocked_message)
	{
		$this->blocked_message = $blocked_message;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function isBlocked()
	{
		return $this->is_blocked;
	}

	/**
	 * @param boolean $is_blocked
	 * @return $this
	 */
	public function setIsBlocked($is_blocked)
	{
		$this->is_blocked = $is_blocked;
		return $this;
	}

	public function getKr()
	{
		return \common\helpers\Convert::getMoneyFormat($this->getKrBalance());
	}

	public function getEkr()
	{
		return \common\helpers\Convert::getMoneyFormat($this->getEkrBalance());
	}

	public function getVoucher()
	{
		return \common\helpers\Convert::getMoneyFormat($this->getVoucherBalance());
	}

	public function getGold()
	{
		return \common\helpers\Convert::getMoneyFormat($this->getGoldBalance());
	}

	public function getActiveBalanceCount()
	{
		if($this->getActiveBalance() == self::TYPE_KR)
			return $this->getKrBalance();

		if($this->getActiveBalance() == self::TYPE_EKR)
			return $this->getEkrBalance();

		if($this->getActiveBalance() == self::TYPE_GOLD)
			return $this->getGoldBalance();

		//if($this->getActiveBalance() == self::TYPE_VOUCHER)
		//	return $this->getVoucherBalance();

		return 0.00;
	}

    public function takeActiveBalanceCount($price)
    {
        if($this->getActiveBalance() == self::TYPE_KR)
            $this->takeKrBalance($price);

        if($this->getActiveBalance() == self::TYPE_EKR)
            $this->takeEkrBalance($price);

		if($this->getActiveBalance() == self::TYPE_GOLD)
			$this->takeGoldBalance($price);
    }

	public function isActiveKr()
	{
		return $this->getActiveBalance() == User::TYPE_KR;
	}

	public function isActiveEkr()
	{
		return $this->getActiveBalance() == User::TYPE_EKR;
	}

	public function isActiveGold()
	{
		return $this->getActiveBalance() == User::TYPE_GOLD;
	}

	public function buildLogin()
	{
        $align = $this->align;
        if(!$align) $align = 0;
		$string = CHtml::image('http://i.oldbk.com/i/align_'.$align.'.gif').' ';
		if($this->klan !== null && $this->klan != '')
			$string .= CHtml::image('http://i.oldbk.com/i/klan/'.$this->klan.'.gif', $this->klan, array('title' => $this->klan)).' ';

		$string .= '<span style="vertical-align:middle;">'.CHtml::link($this->login.' <span class="level">['.$this->level.']</span>', 'javascript:void(0)', ['class' => 'login']).'</span>';
		$string .= CHtml::link(
			CHtml::image('http://i.oldbk.com/i/inf.gif', '', array('style' => 'margin-bottom:2px')), 'http://oldbk.com/inf.php?'.$this->game_id, array(
				'target' => '_blank')
		);
		return $string;
	}

	public function getFreeDailyLimit()
	{
		$criteria = new CDbCriteria();
		$criteria->select = 'sum(`t`.price) as sum';
		$criteria->addCondition('`t`.user_id = :user_id');
		$criteria->addCondition("DATE_FORMAT(`t`.create_at_datetime, '%d.%m.%Y') = :datetime");
		$criteria->addCondition('`t`.result_status != :refund');
		$criteria->addCondition('`t`.price_type = :price_type');
		$criteria->params = [
			':datetime' => date('d.m.Y'),
			':user_id' => Yii::app()->getUser()->getId(),
			':price_type' => $this->getActiveBalance(),
            ':refund' => UserBetting::RESULT_RETURN
		];
		/** @var UserBetting $model */
		$model = UserBetting::model()->find($criteria);
		if(!$model) $sum = 0;
        else $sum = $model->sum;
        if($this->getDailyLimit() - $sum <= 0)
            return 0;
        else
            return $this->getDailyLimit() - $sum;
	}

	public function getDailyLimit()
	{
		return ($this->level - 7) * 100;
	}

    public function getActiveBalanceDiff($price_type)
    {
        if(!$this->hasRelated('userActiveBalances') && !$this->userActiveBalances)
            return 0.00;
        foreach ($this->userActiveBalances as $ActiveBalance)
        {
            if($ActiveBalance->getPriceType() != $price_type) continue;
            return $ActiveBalance->getActiveDiff();
        }

        return 0.00;
    }

    public function onAfterChange($event)
    {
        $this->raiseEvent('onAfterChange', $event);
    }

    public function updateAction()
    {
        $r = $this->save();

        if($r && $this->hasEvent('onAfterChange')) {
            $this->onAfterChange(new \CEvent($this));
        }

        return $r;
    }

    public function createAction()
    {
        $t = Yii::app()->db->beginTransaction();
        try {
            if(!$this->save(false)) {
                Yii::app()->getAjax()->addErrors($this);
                throw new Exception();
            }

            $t->commit();
            return true;
        } catch (Exception $ex) {
            $t->rollback();
            MException::logMongo($ex);
        }

        return false;
    }

    private $_identity;
    public function login()
    {
        if($this->_identity === null)
        {
            $this->_identity = new \common\components\UserIdentity($this->getLogin(), null, $this->getGameId());
            $this->_identity->authenticate();
        }
        if($this->_identity->errorCode === \common\components\UserIdentity::ERROR_NONE)
        {
            //$this->setAttributes($this->_identity->getModel()->getAttributes(), false);
            $duration = 3600;
            Yii::app()->getUser()->login($this->_identity, $duration);
            return true;
        }
        else
            return false;
    }

	public function sendBalanceToFront()
	{
		$event = Yii::app()->getNodeSocket()->getFrameFactory()->createUserEventFrame();
		$event->setUserId($this->getId());
		$event->setEventName('exCommand');
		$event['name'] = '$user.updateBalance';
		$event['params'] = [
			[
				'kr' 		=> $this->getKr(),
				'ekr' 		=> $this->getEkr(),
				'gold' 		=> $this->getGold(),
				'active' 	=> $this->getActiveBalance(),
			]
		];
		$event->send();
	}
}