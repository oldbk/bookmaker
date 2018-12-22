<?php
namespace common\components;
/**
 * Class WebUser контейнер пользователя, который зашел на портал
 *
 * @property integer $id
 *
 * @package application.components
 */
use CWebUser;
use User;
use Yii;

class WebUser extends CWebUser
{
    /** @var User */
    private $_model = null;

    public function init()
    {
        parent::init();
    }

    protected function afterLogin($fromCookie) {
        parent::afterLogin($fromCookie);

        $frame = Yii::app()->getNodeSocket()->getFrameFactory()->createAuthenticationFrame();
        $frame->setUserId($this->getId());
        $frame->send();
    }

    private static $_admins = ['rаdminion', 'adminion', 'radminion'];
    //private static $_admin_game_ids = [182783, 8540, 546433];
    public function isAdmin()
    {
        $model = $this->loadUser($this->id);
        if(!$model) return false;

        return in_array(mb_strtolower($model->getKlan()), static::$_admins);
    }

    public static function getAdminIds()
    {
        $criteria = new \CDbCriteria();
        $criteria->addInCondition('`t`.klan', static::$_admins);
        //$criteria->addNotInCondition('`t`.game_id', [457757]);
        $criteria->select = ['id'];
        $criteria->index = 'id';
        $Users = User::model()->findAll($criteria);

        return array_keys($Users);
    }

    /**
     * @param null $id
     * @return User
     */
    protected function loadUser($id = null)
    {
        if($this->_model === null && $id !== null)
            $this->_model = User::model()->findByPk($id);

        if($this->_model)
            $this->updateStates($this->_model);
        return $this->_model;
    }

    public function getGameId()
    {
        return $this->getState('game_id');
    }

    public function getLoginHTML()
    {
        $model = $this->loadUser($this->id);
        if(!$model) return null;

        return $model->buildLogin();
    }

    public function isActiveKr()
    {
        $model = $this->loadUser($this->id);
        if(!$model) return false;

        return $model->isActiveKr();
    }

    public function isActiveEkr()
    {
        $model = $this->loadUser($this->id);
        if(!$model) return false;

        return $model->isActiveEkr();
    }

	public function isActiveGold()
	{
		$model = $this->loadUser($this->id);
		if(!$model) return false;

		return $model->isActiveGold();
	}

    public function getKr()
    {
        $model = $this->loadUser($this->id);
        if(!$model) return 0.00;

        return $model->getKr();
    }

    public function getEkr()
    {
        $model = $this->loadUser($this->id);
        if(!$model) return 0.00;

        return $model->getEkr();
    }

    public function getVoucher()
    {
        $model = $this->loadUser($this->id);
        if(!$model) return 0.00;

        return $model->getVoucher();
    }

	public function getGold()
	{
		$model = $this->loadUser($this->id);
		if(!$model) return 0.00;

		return $model->getGold();
	}

    /**
     * @return User
     * @deprecated
     */
    public function getModel()
    {
        return $this->loadUser($this->id);
    }

    /**
     * @return User
     */
    public function model()
    {
        return $this->loadUser($this->id);
    }

    public function getActiveBalanceCount()
    {
        $model = $this->loadUser($this->id);
        if(!$model) return 0.00;

        return $model->getActiveBalanceCount();
    }

    /**
     * @return int
     * @deprecated
     */
    public function getActiveBalanceType()
    {
        $model = $this->loadUser($this->id);
        if(!$model) return User::TYPE_KR;

        return $model->getActiveBalance();
    }

    public function getAB()
    {
        $model = $this->loadUser($this->id);
        if(!$model)
            throw new \CHttpException('Вы не авторизованы');

        return $model->getActiveBalance();
    }

    public function getFreeDailyLimit()
    {
        $model = $this->loadUser($this->id);
        if(!$model) return 0.00;

        return $model->getFreeDailyLimit();
    }

    public function getLevel()
    {
        return $this->getState('level');
    }

    public function getAlign()
    {
        return $this->getState('align');
    }

    public function isBlocked()
    {
        return $this->getState('is_blocked');
    }

    private $_ips = [
        //'62.90.164.82' => 3, Архитектор
        '178.151.80.59' => 1, //Байт
        //'91.149.145.153' => 2, //
        '31.154.88.236' => 3, //Архитектор
        '31.154.93.98' => 3, //Архитектор
        '78.111.187.175' => 1,
        //'85.175.4.80' => 2320,
        '127.0.0.1' => 1,
        '78.111.186.231' => 1,
        '84.95.37.197' => 3
    ];

    public function isAdminIp($ip)
    {
        //VarDumper::dump($ip);die;
        if(array_key_exists($ip, $this->_ips))
            return $this->_ips[$ip];

        return false;
    }

    /**
     * @param User $model
     */
    private function updateStates($model)
    {
        $states = [
            'is_blocked' => $model->isBlocked(),
            'game_id' => $model->getGameId(),
            'level' => $model->getLevel(),
            'klan' => $model->getKlan(),
            'align' => $model->getAlign(),
        ];
        foreach ($states as $key => $value)
            $this->setState($key, $value);
    }
}