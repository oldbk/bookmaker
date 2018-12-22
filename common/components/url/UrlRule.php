<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 08.10.2014
 * Time: 1:18
 */

class UrlRule extends CUrlRule
{
    public $sitePart;

    public function parseUrl($manager,$request,$pathInfo,$rawPathInfo)
    {
        if($this->sitePart !== null && $this->sitePart !== Yii::app()->params['sitePart'])
            return false;

        if($this->verb!==null && !in_array($request->getRequestType(), $this->verb, true))
            return false;

        if($manager->caseSensitive && $this->caseSensitive===null || $this->caseSensitive)
            $case='';
        else
            $case='i';

        if($this->urlSuffix!==null)
            $pathInfo=$manager->removeUrlSuffix($rawPathInfo,$this->urlSuffix);

        // URL suffix required, but not found in the requested URL
        if($manager->useStrictParsing && $pathInfo===$rawPathInfo)
        {
            $urlSuffix=$this->urlSuffix===null ? $manager->urlSuffix : $this->urlSuffix;
            if($urlSuffix!='' && $urlSuffix!=='/')
                return false;
        }

        if($this->hasHostInfo)
            $pathInfo=strtolower($request->getHostInfo()).rtrim('/'.$pathInfo,'/');

        $pathInfo.='/';

        if(preg_match($this->pattern.$case,$pathInfo,$matches))
        {
            foreach($this->defaultParams as $name=>$value)
            {
                if(!isset($_GET[$name]))
                    $_REQUEST[$name]=$_GET[$name]=$value;
            }
            $tr=array();
            foreach($matches as $key=>$value)
            {
                if(isset($this->references[$key]))
                    $tr[$this->references[$key]]=$value;
                elseif(isset($this->params[$key]))
                    $_REQUEST[$key]=$_GET[$key]=$value;
            }
            if($pathInfo!==$matches[0]) // there're additional GET params
                $manager->parsePathInfo(ltrim(substr($pathInfo,strlen($matches[0])),'/'));
            if($this->routePattern!==null)
                return strtr($this->route,$tr);
            else
                return $this->route;
        }
        else
            return false;
    }

    public function __construct($route, $pattern)
    {
        if(is_array($route) && isset($route['sitePart']))
            $this->sitePart = $route['sitePart'];

        parent::__construct($route, $pattern);
    }
} 