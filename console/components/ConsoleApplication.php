<?php
/**
 * Base class for console application, so we will have control over it.
 *
 * Some tweaks for console can end here.
 *
 * @package YiiBoilerplate\Console
 */
class ConsoleApplication extends CConsoleApplication
{
    public $controllerMap;

    /**
     * @return \common\components\Request
     */
    public function getRequest()
    {
        return parent::getRequest();
    }

    /**
     * @return \common\components\oldbk\Oldbk
     */
    public function getOldbk()
    {
        return $this->getComponent('oldbk');
    }

    /**
     * @return \common\components\updater\Sport
     */
    public function getSport()
    {
        return $this->getComponent('sport');
    }

    /**
     * @return YiiNewRelic
     */
    public function getNewRelic()
    {
        return $this->getComponent('newRelic');
    }

    /**
     * @return NodeSocket
     */
    public function getNodeSocket()
    {
        return $this->getComponent('nodeSocket');
    }
} 