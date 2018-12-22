<?php
namespace common\widgets\editor;
/**
 * ImperaviRedactorWidget class file.
 *
 * @property string $assetsPath
 * @property string $assetsUrl
 * @property array $plugins
 *
 * @author Veaceslav Medvedev <slavcopost@gmail.com>
 * @author Alexander Makarov <sam@rmcreative.ru>
 *
 * @version 1.3.5
 *
 * @link https://github.com/yiiext/imperavi-redactor-widget
 * @link http://imperavi.com/redactor
 * @license https://github.com/yiiext/imperavi-redactor-widget/blob/master/license.md
 */
use CInputWidget;
use CHtml;
use Yii;
use CJavaScript;
use CClientScript;
class ImperaviRedactorWidget extends CInputWidget
{
	/**
	 * Assets package ID.
	 */
	const PACKAGE_ID = 'imperavi-redactor';

	/**
	 * @var array {@link http://imperavi.com/redactor/docs/ redactor options}.
	 */
	public $options = array();

	/**
	 * @var string|null Selector pointing to textarea to initialize redactor for.
	 * Defaults to null meaning that textarea does not exist yet and will be
	 * rendered by this widget.
	 */
	public $selector;

	/** @var array */
	public $package = array();

	/** @var array */
	private $_plugins = array();

    /** @var \common\components\StaticContent */
    private $widget;

	/**
	 * Init widget.
	 */
	public function init()
	{
		parent::init();
        $this->widget = Yii::app()->getStatic()->setWidget('imperavi-redactor');

		if ($this->selector === null) {
			list($this->name, $this->id) = $this->resolveNameID();
			$this->htmlOptions['id'] = $this->getId();
			$this->selector = '#' . $this->getId();

			if ($this->hasModel()) {
				echo CHtml::activeTextArea($this->model, $this->attribute, $this->htmlOptions);
			} else {
				echo CHtml::textArea($this->name, $this->value, $this->htmlOptions);
			}
		}

		$this->registerClientScript();
	}

	/**
	 * Register CSS and Script.
	 */
	protected function registerClientScript()
	{
        $this->widget
            ->registerScriptFile(YII_DEBUG ? 'redactor.js' : 'redactor.min.js')
            ->registerCssFile('redactor.css');

		// Append language file to script package.
		if (isset($this->options['lang']) && $this->options['lang'] !== 'en') {
            $this->widget->registerLangFile($this->options['lang'] . '.js');
		}

		// Add assets url to relative css.
		if (isset($this->options['css'])) {
			if (!is_array($this->options['css'])) {
				$this->options['css'] = array($this->options['css']);
			}
			foreach ($this->options['css'] as $i => $css) {
				if (strpos($css, '/') === false) {
					$this->options['css'][$i] = $this->widget->getLink('css/'.$css);
				}
			}
		}

		// Insert plugins in options
		if (!empty($this->_plugins)) {
			$this->options['plugins'] = array_keys($this->_plugins);
		}

		$clientScript = Yii::app()->getClientScript();
		$selector = CJavaScript::encode($this->selector);
		$options = CJavaScript::encode($this->options);

		$clientScript
			->addPackage(self::PACKAGE_ID, $this->package)
			->registerPackage(self::PACKAGE_ID)
			->registerScript(
				$this->id,
				'jQuery(' . $selector . ').redactor(' . $options . ');',
				CClientScript::POS_READY
			);

		foreach ($this->getPlugins() as $id => $plugin) {
			$clientScript
				->addPackage(self::PACKAGE_ID . '-' . $id, $plugin)
				->registerPackage(self::PACKAGE_ID . '-' . $id);
		}
	}

	/**
	 * @param array $plugins
	 */
	public function setPlugins(array $plugins)
	{
		foreach ($plugins as $id => $plugin) {
			if (!isset($plugin['baseUrl']) && !isset($plugin['basePath'])) {
				$plugin['baseUrl'] = Yii::app()->getStatic()->setWidget('imperavi-redactor')->getLink('/plugins/' . $id);
			}

			$this->_plugins[$id] = $plugin;
		}
	}

	/**
	 * @return array
	 */
	public function getPlugins()
	{
		return $this->_plugins;
	}
}
