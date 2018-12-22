<?php
/**
 * Created by PhpStorm.
 * User: Ice
 * Date: 13.07.14
 * Time: 1:53
 */
namespace common\components;

class Ajax extends \CApplicationComponent
{
    private $_messages = array();
    private $_other = array();
    private $_errors = array();
    private $_url = null;
    private $_js = [];
    private $_replace = [];
    private $_html = [];
    private $_triggers = [];
    private $_isMenu = true;

    private $_typeReplace = [
        'danger' => 'error',
        'info' => 'information'
    ];

    /**
     * @param $name
     * @param $params
     * @return $this
     */
    public function runJS($name, $params = [])
    {
        if(!is_array($params))
            $params = [$params];

        $this->_js[$name] = $params;

        return $this;
    }

    private $_loadJS = [];

    /**
     * @param $url
     * @return $this
     */
    public function loadJS($url)
    {
        $this->_loadJS[] = $url;

        return $this;
    }

    private $_loadCSS = [];

    /**
     * @param $url
     * @return $this
     */
    public function loadCSS($url)
    {
        $this->_loadCSS[] = $url;

        return $this;
    }

    /**
     * @param $url
     * @return $this
     */
    public function setUrl($url)
    {
        if(is_array($url) && isset($url['route']) && isset($url['params']))
            $this->_url = \Yii::app()->createUrl($url['route'], $url['params']);
        else
            $this->_url = $url;

        return $this;
    }

    /**
     * @param $view
     * @param $selector
     * @param $params
     * @param $render
     * @return $this
     */
    public function addReplace($view, $selector, $params = [], $render = true)
    {
        if($render)
            $view = \Yii::app()->getController()->renderPartial($view, $params, true);
        $this->_replace[] = [
            'view' => $view,
            'selector' => $selector,
        ];

        return $this;
    }

    /**
     * @param $view
     * @param $selector
     * @param $params
     * @param $render
     * @return $this
     */
    public function addHtml($view, $selector, $params = [], $render = true)
    {
        if($render)
            $view = \Yii::app()->getController()->renderPartial($view, $params, true);
        $this->_html[] = [
            'view' => $view,
            'selector' => $selector,
        ];

        return $this;
    }

    /**
     * @param $messages
     * @param string $type
     * @return $this|void
     */
    public function addMessage($messages, $type = 'success')
    {
        $type = str_replace(array_keys($this->_typeReplace), array_values($this->_typeReplace), $type);
        if(!is_array($messages))
            $messages = [$messages];

        foreach ($messages as $message) {
            if(isset($this->_messages[$type]) && in_array($message, $this->_messages[$type]))
                continue;

            $this->_messages[$type][] = $message;
        }

        return $this;
    }

    /**
     * @param $triggers
     * @return $this
     */
    public function addTrigger($triggers)
    {
        if(!is_array($triggers))
            $triggers = [$triggers];

        foreach ($triggers as $trigger) {
            if(!in_array($trigger, $this->_triggers))
                $this->_triggers[] = $trigger;
        }

        return $this;
    }

    public function getMessages()
    {
        return $this->_messages;
    }

    public function hasErrors()
    {
        return !empty($this->_errors);
    }

    /**
     * @param $type
     * @param array|string|\CModel $model
     * @param null $number
     * @return $this
     */
    public function addErrors($model, $type = 'danger', $number = null)
    {
        $type = str_replace(array_keys($this->_typeReplace), array_values($this->_typeReplace), $type);
        if(is_object($model) !== false) {
            foreach($model->getErrors() as $attribute=>$errors) {
                if($number === null)
                    $this->_errors[$type][\CHtml::activeId($model,$attribute)] = $errors[0];
                else
                    $this->_errors[$type][get_class($model).'_'.$number.'_'.$attribute] = $errors;
            }
        } else {
            if(!is_array($model))
                $this->_errors[$type][] = $model;
            else {
                foreach($model as $field => $error)
                    $this->_errors[$type][$field] = $error[0];
            }
        }

        return $this;
    }

    /**
     * @param array $other
     * @return $this
     */
    public function addOther(array $other)
    {
        $this->_other = \CMap::mergeArray($this->_other, $other);

        return $this;
    }

    public function menu($isBuild = false)
    {
        $this->_isMenu = $isBuild;

        return $this;
    }

    public function send($url = null)
    {
        $params = [];
        $menu = $this->getMenu();
        if($url !== null) $this->_url = $url;

        $params['runJS'] = [];
        foreach ($this->_js as $name => $jsArgs)
            $params['runJS'][] = ['name' => $name, 'params' => $jsArgs];
        $params['runJS'][] = ['name' => '$ajax.loadScript', 'params' => [\Yii::app()->getClientScript()->getJS()]];
        $params['runJS'][] = ['name' => '$ajax.loadStyle', 'params' => [\Yii::app()->getClientScript()->getCSS()]];

        if($this->_url)                     $params['redirectLink'] = $this->_url;
        if(!empty($this->_errors))          $params['errors'] = $this->_errors;
        if(!empty($this->_messages))        $params['messages'] = $this->_messages;
        if(!empty($this->_replace))         $params['replaceList'] = $this->_replace;
        if(!empty($this->_html))            $params['htmlList'] = $this->_html;
        if(!empty($this->_triggers))        $params['triggers'] = $this->_triggers;
        if(!empty($menu) && $this->_isMenu) $params['menu'] = $menu;

        if(\Yii::app()->getController()->hasPageTitle())
            $params['pageTitle'] = \Yii::app()->getController()->getPageTitle();

        $params = \CMap::mergeArray($this->_other, $params);

        header('Content-type: application/json');
        echo \CJSON::encode($params);
        \Yii::app()->end();
    }

    private function getMenu()
    {
        $menuList = [];
        foreach (\Yii::app()->getController()->menu()->getList() as $name => $menu) {
            if(empty($menu))
                $menuList[$name] = false;
            else {
                $menuList[$name] = \Yii::app()->getController()->widget('\common\widgets\menu\MenuWidget', [
                    'items' => $menu,
                    'view' => $name
                ], true);
            }
        }

        return $menuList;
    }
} 