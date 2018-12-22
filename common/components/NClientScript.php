<?php
namespace common\components;
/**
 * Created by PhpStorm.
 * User: me
 * Date: 18.02.2015
 * Time: 2:31
 */

use Yii;
Yii::import('common.extensions.yii-newrelic.YiiNewRelicClientScript');
class NClientScript extends \YiiNewRelicClientScript
{
    protected $callbacks = [];
    public function registerScriptCallback($url, $position = null, $callback = null, array $htmlOptions = array())
    {
        parent::registerScriptFile($url, $position, $htmlOptions);

        if($callback)
            $this->callbacks[$url] = $callback;

        return $this;
    }

    public function getJS()
    {
        if(!empty($this->scriptMap))
            $this->remapScripts();

        $js_list = [];
        foreach ($this->scriptFiles as $files) {
            foreach ($files as $name => $js) {
                $callback = [];
                if(isset($this->callbacks[$js]))
                    $callback = $this->callbacks[$js];

                $js_list[$js] = \CJSON::encode($callback);
            }
        }

        return $js_list;
    }

    public function getCSS()
    {
        if(!empty($this->scriptMap))
            $this->remapScripts();

        return array_keys($this->cssFiles);
    }

    public function renderJSON(&$data)
    {

    }
}