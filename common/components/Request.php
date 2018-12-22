<?php
namespace common\components;
/**
 * Created by PhpStorm.
 * User: me
 * Date: 08.10.2014
 * Time: 6:46
 */

use CHttpRequest;
use Yii;
use CHttpException;
class Request extends CHttpRequest
{
    public function validateCsrfToken($event)
    {
        if($this->getIsPostRequest())
        {
            if(Yii::app()->getOldbk()->validateRequest($this->getUrlReferrer())) {
                $valid = true;
                return $valid;
            }

            $cookies=$this->getCookies();
            if($cookies->contains($this->csrfTokenName) && isset($_POST[$this->csrfTokenName]) || isset($_GET[$this->csrfTokenName] ))
            {
                $tokenFromCookie=$cookies->itemAt($this->csrfTokenName)->value;
                $tokenFrom=!empty($_POST[$this->csrfTokenName]) ? $_POST[$this->csrfTokenName] : $_GET[$this->csrfTokenName];
                $valid=$tokenFromCookie===$tokenFrom;
            }
            else
                $valid=false;
            if(!$valid)
                throw new CHttpException(400,Yii::t('yii','Lite: The CSRF token could not be verified.'));
        }

        return true;
    }

    public function getIpAddress()
    {
        return isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $_SERVER['REMOTE_ADDR'];
    }

    public function getLanguage()
    {
        return $_SERVER['HTTP_ACCEPT_LANGUAGE'];
    }

    public function getUrlReferrer()
    {
        return isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:Yii::app()->createUrl('/');
    }
} 