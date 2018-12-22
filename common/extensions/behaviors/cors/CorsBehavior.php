<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 08.10.2014
 * Time: 7:54
 */

namespace common\extensions\behaviors\cors;
\Yii::import('vendor.iachilles.cors-behavior.CorsBehavior');

use CorsBehavior as BaseCorsBehavior;
class CorsBehavior extends BaseCorsBehavior
{
    private $_allowOrigin;

    protected function setAllowOriginHeader($origin)
    {
        header('Access-Control-Allow-Origin: ' . $origin);
        header('Access-Control-Allow-Credentials: true');

        if(\Yii::app()->getRequest()->getParam('request_width'))
            $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
    }

    public function onBeginRequestHandler($event)
    {
        if (is_null($this->_allowOrigin) || php_sapi_name() == "cli")
        {
            return;
        }

        if ($this->checkAllowedRoute())
        {

            $origin = $this->parseHeaders();

            if ($origin !== false)
            {
                $this->setAllowOriginHeader($origin);
            }
        }
    }
} 