<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 12.08.14
 * Time: 16:01
 */

namespace common\extensions\html\editable;

\Yii::import('booster.widgets.TbEditable');
use TbEditable;
class MEditable extends TbEditable
{
    public function registerClientScript()
    {
        $selector = "a[rel=\"{$this->htmlOptions['rel']}\"]";
        if($this->liveTarget) {
            $selector = '#'.$this->liveTarget.' '.$selector;
        }
        $script = "$('".$selector."')";

        //attach events
        foreach(array('init', 'shown', 'save', 'hidden') as $event) {
            $eventName = 'on'.ucfirst($event);
            if (isset($this->$eventName)) {
                // CJavaScriptExpression appeared only in 1.1.11, will turn to it later
                //$event = ($this->onInit instanceof CJavaScriptExpression) ? $this->onInit : new CJavaScriptExpression($this->onInit);
                $eventJs = (strpos($this->$eventName, 'js:') !== 0 ? 'js:' : '') . $this->$eventName;
                $script .= "\n.on('".$event."', ".\CJavaScript::encode($eventJs).")";
            }
        }

        //apply editable
        $options = \CJavaScript::encode($this->options);
        $script .= ".editable($options);";

        //wrap in anonymous function for live update
        if($this->liveTarget) {
            $script .= "\n $('body').one('ajaxUpdate.editable', function(e){ if(e.target.id == '".$this->liveTarget."') yiiEditable(); });";
            $script = "(function yiiEditable() {\n ".$script."\n}());";
        }

        \Yii::app()->getClientScript()->registerScript(__CLASS__ . '-' . $selector, $script);

        return $script;
    }
} 