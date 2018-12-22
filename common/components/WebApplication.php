<?php
/**
 * Base class for our web application.
 *
 * Here will be the tweaks affecting the behavior of all entry points.
 *
 * @package YiiBoilerplate
 *
 * @property ARedisConnection redis
 * @property ARedisCache cache
 * @property \common\components\Ajax ajax
 * @property \common\components\StaticContent static
 * @property \common\components\WebUser user
 * @property EasyImage easyImage
 * @property EMongoClient mongodb
 * @property Curl curl
 * @property \common\components\Curl phantom
 * @property \common\components\ImageUpload imageUpload
 * @property \common\components\CImageHandler ih
 * @property \common\components\Request request
 * @property \common\components\Parimatch parimatch
 * @property \common\components\updater\Sport sport
 * @property \common\components\NClientScript clientScript
 * @property \common\components\oldbk\Oldbk oldbk
 * @property \common\components\CheckValidOutput checker
 * @property YiiNewRelic newRelic
 * @property NodeSocket nodeSocket
 *
 */
class WebApplication extends CWebApplication
{
	public function beforeControllerAction($controller, $action) {
		Yii::app()->newRelic->setTransactionName($controller->id, $action->id);
		return parent::beforeControllerAction($controller, $action);
	}

	protected function init()
	{
		register_shutdown_function(array($this, 'onShutdownHandler'));
		parent::init();
	}

	public function onShutdownHandler()
	{
		// 1. error_get_last() returns NULL if error handled via set_error_handler
		// 2. error_get_last() returns error even if error_reporting level less then error
		$e = error_get_last();

		$errorsToHandle = E_ERROR | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING;
		if(!is_null($e) && ($e['type'] & $errorsToHandle)) {
			$Critical = new Critical();
			$Critical->setOther($e)
				->setUserId(Yii::app()->getUser()->getId())
				->setPost($_POST)
				->setGet($_GET);
			foreach($e as $name => $value)
				$Critical->setAttribute($name, $value);
			$Critical->save();

			$this->handleError($e['type'], $e['message'], $e['file'], $e['line']);
		}
	}

	/**
     * Workaround to fallback to `en` locale even if something unknown to us was requested.
     *
     * @param string $localeID
     * @return CLocale
	 */
	public function getLocale($localeID = null)
    {
		try
        {
			return parent::getLocale($localeID);
		}
        catch (Exception $e)
        {
			return CLocale::getInstance('en');
		}
	}

	/**
	 * @return \common\components\WebUser
	 */
	public function getUser()
	{
		return parent::getUser();
	}

	/**
	 * @return \common\components\Request
	 */
	public function getRequest()
	{
		return parent::getRequest();
	}

	/**
	 * @return \common\components\Ajax
	 */
	public function getAjax()
	{
		return $this->getComponent('ajax');
	}

	/**
	 * @return \common\components\oldbk\Oldbk
	 */
	public function getOldbk()
	{
		return $this->getComponent('oldbk');
	}

	/**
	 * @return \common\components\StaticContent
	 */
	public function getStatic()
	{
		return $this->getComponent('static');
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

	/**
	 * @return \common\components\NClientScript
	 */
	public function getClientScript()
	{
		return parent::getClientScript();
	}

	/**
	 * @return \common\components\Controller
	 */
	public function getController()
	{
		return parent::getController();
	}
}
