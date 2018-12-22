<?php
namespace common\components;
use CUserIdentity;
use CDbCriteria;
use User;
/**
 * Created by PhpStorm.
 * User: me
 * Date: 16.09.2014
 * Time: 1:38
 */

class UserIdentity extends CUserIdentity
{
    /**
     * @var int id
     */
    private $_id;

    /** @var \User */
    private $_model;
    private $_game_id;

    const ERROR_LOGIN_INVALID = 1;

    public function __construct($username, $password, $game_id)
    {
        parent::__construct($username, $password);
        $this->_game_id = $game_id;
    }


    /**
     * Authenticates a user.
     * The example implementation makes sure if the email and password
     * are both 'demo'.
     * In practical applications, this should be changed to authenticate
     * against some persistent user identity storage (e.g. database).
     * @return boolean whether authentication succeeds.
     */
    public function authenticate()
    {
        $criteria = new CDbCriteria();
        $criteria->addCondition('game_id = :game_id');
        $criteria->params = [':game_id' => $this->_game_id];

        /** @var User $model */
        $this->_model = $model = User::model()->find($criteria);
        if($model === null)
            $this->errorCode = self::ERROR_LOGIN_INVALID;
        else {
            $this->_id = $model->getId();
            $this->username = $model->getLogin();
            $this->errorCode = self::ERROR_NONE;
            $states = [
                'is_blocked' => $model->isBlocked(),
                'game_id' => $model->getGameId(),
                'level' => $model->getLevel(),
                'klan' => $model->getKlan(),
                'align' => $model->getAlign(),
            ];
            $this->setPersistentStates($states);
        }

        return !$this->errorCode;
    }

    /**
     * @return integer the ID of the user record
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @return User
     */
    public function getModel()
    {
        return $this->_model;
    }
} 