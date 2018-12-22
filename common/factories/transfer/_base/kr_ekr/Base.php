<?php
namespace common\factories\transfer\_base\kr_ekr;

/**
 * Created by PhpStorm.
 */

abstract class Base
{
    /** @var \User */
    private $user;

    /** @var null  */
    protected $error_msg = null;

    /** @var \BettingGroup */
    protected $betGroup;

    /** @var int */
    protected $bank_id;


    /**
     * @param $user
     */
    public function __construct(\User &$user)
    {
        $this->setUser($user);
    }

    /**
     * @return float|null
     */
    abstract protected function getStrangeValue();

    /**
     * @return int
     */
    abstract protected function getPriceType();

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->getUser()->getId();
    }

    /**
     * @return mixed
     */
    public function getUserGameId()
    {
        return $this->getUser()->getGameId();
    }

    /**
     * @return \User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param \User $user
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return null
     */
    public function getErrorMsg()
    {
        return $this->error_msg;
    }

    /**
     * @param null $error_msg
     * @return $this
     */
    public function setErrorMsg($error_msg)
    {
        $this->error_msg = $error_msg;
        return $this;
    }

    /**
     * @return \BettingGroup
     */
    public function getBetGroup()
    {
        return $this->betGroup;
    }

    /**
     * @param \BettingGroup $betGroup
     * @return $this
     */
    public function setBetGroup($betGroup)
    {
        $this->betGroup = $betGroup;
        return $this;
    }

    /**
     * @return int
     */
    public function getBankId()
    {
        return $this->bank_id;
    }

    /**
     * @param int $bank_id
     * @return $this
     */
    public function setBankId($bank_id)
    {
        $this->bank_id = $bank_id;
        return $this;
    }
}