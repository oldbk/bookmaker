<?php
namespace common\extensions\behaviors\menu;

use common\components\Controller;
use common\components\VarDumper;
use Yii;
use CBehavior;
use CAction;

Yii::import('common.extensions.behaviors.menu.menu.*');
/**
 * Class MenuBehaviors Класс для построения меню
 * Created by JetBrains PhpStorm.
 * User: Николай
 * Date: 14.06.13
 * Time: 19:42
 * To change this template use File | Settings | File Templates.
 *
 * @package application.behaviors.menu
 *
 * @method Controller getOwner()
 */
class MenuBehaviors extends CBehavior
{
    const MENU_HEAD = 'headMenu';
    const MENU_MAIN = 'mainMenu';
    const MENU_ADMIN = 'uMenu';
    const MENU_SPORT = 'sportMenu';

    public $mainMenu = [];
    public $uMenu = [];
    public $headMenu = [];
    public $sportMenu = [];

    /**
     *
     */
    public function __construct() {
        $this->init();
    }

    /** @var array  */
    private $menuShowArray = [
        self::MENU_HEAD => [
            '*' => 'run',
        ],
        self::MENU_MAIN => [
            '*' => 'run',
        ],
        self::MENU_ADMIN => [
            '*' => 'run',
        ],
        self::MENU_SPORT => [
            'sport.football.*'    => 'football',
            'sport.tennis.*'      => 'tennis',
            'sport.basketball.*'  => 'basketball',
            'sport.hokkey.*'      => 'hokkey',
        ],
    ];

    /**
     *
     */
    public function init() {
    }

    /**
     * @return array
     */
    public function getMainMenu()
    {
        return $this->mainMenu;
    }

    /**
     * @param array $mainMenu
     * @return $this
     */
    public function setMainMenu($mainMenu)
    {
        $this->mainMenu = $mainMenu;
        return $this;
    }

    /**
     * @return array
     */
    public function getAdminMenu()
    {
        return $this->uMenu;
    }

    /**
     * @param array $adminMenu
     * @return $this
     */
    public function setAdminMenu($adminMenu)
    {
        $this->uMenu = $adminMenu;
        return $this;
    }

    /**
     * @return array
     */
    public function getHeadMenu()
    {
        return $this->headMenu;
    }

    /**
     * @param array $headMenu
     * @return $this
     */
    public function setHeadMenu($headMenu)
    {
        $this->headMenu = $headMenu;
        return $this;
    }

    /**
     * @return array
     */
    public function getSportMenu()
    {
        return $this->sportMenu;
    }

    /**
     * @param array $sportMenu
     * @return $this
     */
    public function setSportMenu($sportMenu)
    {
        $this->sportMenu = $sportMenu;
        return $this;
    }

    private $_action;
    private $_controller;
    private $_module;

    /**
     * @param CAction $action
     * @return array
     */
    private function createMenu($action) {
        $this->_action = $action->id;
        $this->_controller = $action->getController()->id;
        $this->_module = null;
        if(null !== $action->getController()->module)
            $this->_module = $action->getController()->module->id;

        $returned = [];
        foreach($this->menuShowArray as $menuName => $params) {
            $className = 'common\extensions\behaviors\menu\menu\\'.ucfirst($menuName);
            foreach($params as $paramAction => $method) {
                if($paramAction == '*') {
                    $class = new $className();
                    $returned[$menuName] = call_user_func(array($class, $method));
                } else {
                    $flag = true;
                    $route = explode('.', $paramAction);
                    if(count($route) == 3) {
                        $flag = $this->check([$this->_module, $this->_controller, $this->_action], $route);
                    } elseif(count($route) == 2) {
                        $flag = $this->check([$this->_controller, $this->_action], $route);
                    }

                    if($flag) {
                        $class = new $className();
                        $returned[$menuName] = call_user_func(array($class, $method));
                        break;
                    }
                }
            }
        }

        return $returned;
    }

    /**
     * @param CAction $action
     */
    public function buildMenu($action)
    {
        $menuList = $this->createMenu($action);
        foreach ($menuList as $key => $menu)
            $this->{$key} = $menu;
    }

    private function check($array, $route)
    {
        $flag = true;
        foreach ($array as $key => $value) {
            $flag = $route[$key] == '*' || $route[$key] == $value;
            if(!$flag)
                break;
        }

        return $flag;
    }

    public function getList()
    {
        return [
            'headMenu'  => $this->getHeadMenu(),
            'uMenu' => $this->getAdminMenu(),
            'mainMenu'  => $this->getMainMenu(),
            'sportMenu' => $this->getSportMenu(),
        ];
    }
}