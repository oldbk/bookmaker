<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 08.10.2014
 * Time: 1:17
 */

class UrlManager extends CUrlManager
{
    private $mapping = array(
        //'locale' => null,
        //'branch' => null,
        //'env' => null,
        'sitePart' => null,
        'host' => null,
        'domain' => null,
    );
    private $_siteParts = [
        'profile',
        'frontend'
    ];

    public $urlRuleClass = 'UrlRule';

    public function init()
    {
        parent::init();

        $info = explode('.', Yii::app()->getRequest()->getServerName());
        krsort($info);
        $info = array_values($info);

        foreach ($info as $index => $value) {
            if($index == 0)
                $this->mapping['domain'] = $value;
            elseif($index == 1)
                $this->mapping['host'] = $value;
            elseif(in_array($value, $this->_siteParts))
                $this->mapping['sitePart'] = $value;
        }
    }

    public function createUrl($route,$params=array(),$ampersand='&')
    {
        $link = parent::createUrl($route,$params,$ampersand);
        foreach ($this->rules as $rule) {
            if(!is_array($rule) || !isset($rule[0]) || !isset($rule['sitePart']) || trim($route, '/') != $rule[0])
                continue;

            if($rule['sitePart'] != $this->mapping['sitePart']) {
                switch ($rule['sitePart']) {
                    case 'frontend':
                        $link = 'http://' . $this->mapping['host'] . '.' . $this->mapping['domain'] . $link;
                        break;
                    default:
                        $link = 'http://'.$rule['sitePart'].'.' . $this->mapping['host'] . '.' . $this->mapping['domain'] . $link;
                        break;
                }
            }
        }

        return $link;
    }
} 