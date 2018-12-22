<?php
/**
 * Created by PhpStorm.
 * User: Ice
 * Date: 13.07.14
 * Time: 1:53
 */
namespace common\components;

use Yii;
use CClientScript;
class StaticContent extends \CApplicationComponent
{
    private $static_www;
    private $libraries;
    private $widgets;
    private $images;

    private $currPath = null;

    /** @var \CAssetManager */
    private $_publisher;

    public function init()
    {
        parent::init();
        $domain = rtrim(Yii::app()->params['static_domain'], '/');

        $this->_publisher = Yii::app()->assetManager;
        $this->static_www = $domain.'/www/'.Yii::app()->theme->name;
        $this->images =     $domain.'/images';
        $this->libraries =  $domain.'/packages';
        $this->widgets =    $domain.'/widgets/'.Yii::app()->theme->name;
    }

    /**
     * @param $libraryName
     * @return $this
     */
    public function setLibrary($libraryName)
    {
        $this->currPath = $this->libraries.'/'.$libraryName;

        return $this;
    }

    /**
     * @return $this
     */
    public function setWww()
    {
        $this->currPath = $this->static_www;

        return $this;
    }

    /**
     * @param $widgetName
     * @return $this
     */
    public function setWidget($widgetName)
    {
        $this->currPath = $this->widgets.'/'.$widgetName;

        return $this;
    }

    /**
     * @return $this
     */
    public function setImages()
    {
        $this->currPath = $this->images;

        return $this;
    }

    /**
     * @param $filePath
     * @return string
     */
    public function getLink($filePath)
    {
        $filePath = ltrim($filePath, '/');
        return "{$this->currPath}/{$filePath}";
    }

    /**
     * @param $filePath
     * @return string
     */
    public function getPath($filePath)
    {
        $filePath = ltrim($filePath, '/');
        $path = "{$this->staticPath()}/{$filePath}";
        if(!is_dir($path))
            mkdir($path, 0777, true);

        return $path;
    }

    /**
     * @param $fileName
     * @param int $position
     * @param boolean $min
     * @param string $callback
     * @return $this
     */
    public function registerScriptFile($fileName, $position = CClientScript::POS_END, $min = false, $callback = null)
    {
        $fileName = ltrim($fileName, '/');
        if($min) {
            $t = explode('.', $fileName);
            $t[count($t) - 1] = 'min.'.$t[count($t) - 1];
            $fileName = implode('.', $t);
        }
        if(strpos($fileName, 'http') === false)
            $path = "{$this->currPath}/js/{$fileName}";
        else
            $path = $fileName;

        if($callback === null)
            Yii::app()->getClientScript()->registerScriptFile($path, $position);
        else
            Yii::app()->getClientScript()->registerScriptCallback($path, $position, $callback);
        return $this;
    }

    public function registerLangFile($fileName, $position = CClientScript::POS_END)
    {
        $fileName = ltrim($fileName, '/');
        Yii::app()->getClientScript()->registerScriptFile("{$this->currPath}/lang/{$fileName}", $position);

        return $this;
    }

    /**
     * @param $fileName
     * @param $min
     * @return $this
     */
    public function registerCssFile($fileName, $min = false)
    {
        $fileName = ltrim($fileName, '/');
        if($min) {
            $t = explode('.', $fileName);
            $t[count($t) - 1] = 'min.'.$t[count($t) - 1];
            $fileName = implode('.', $t);
        }

        Yii::app()->getClientScript()->registerCssFile("{$this->currPath}/css/{$fileName}");
        return $this;
    }

    /**
     * @return string
     */
    public function staticPath()
    {
        return realpath(Yii::app()->basePath.'/../static');
    }

    /**
     * @param $filePath
     * @param bool $path
     * @return string
     */
    public function imageLink($filePath, $path = false)
    {
        $filePath = ltrim($filePath, '/');

        if($path === false)
            return rtrim(Yii::app()->params['static_domain'], '/')."/{$filePath}";
        else
            return $this->currPath.'/'.$filePath;
    }
} 