<?php
/**
 * Created by PhpStorm.
 */

namespace common\components\oldbk;


class User extends Base
{
    public $game_id;
    public $align;
    public $klan;
    public $login;
    public $level;
    /** @var boolean */
    public $block;


    /**
     * @return mixed
     */
    public function getGameId()
    {
        return $this->game_id;
    }

    /**
     * @param mixed $game_id
     * @return $this
     */
    public function setGameId($game_id)
    {
        $this->game_id = $game_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAlign()
    {
        return $this->align;
    }

    /**
     * @param mixed $align
     * @return $this
     */
    public function setAlign($align)
    {
        $this->align = $align;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getKlan()
    {
        return $this->klan;
    }

    /**
     * @param mixed $klan
     * @return $this
     */
    public function setKlan($klan)
    {
        $this->klan = $klan;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param mixed $login
     * @return $this
     */
    public function setLogin($login)
    {
        $this->login = $login;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param mixed $level
     * @return $this
     */
    public function setLevel($level)
    {
        $this->level = $level;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isBlock()
    {
        return $this->block;
    }

    /**
     * @param boolean $block
     * @return $this
     */
    public function setBlock($block)
    {
        $this->block = $block;
        return $this;
    }
}