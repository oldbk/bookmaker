<?php
/**
 * Generic action to display errors.
 *
 * Usable on both frontend and backend.
 * If the request was AJAX one, just output the message.
 * Otherwise, render error view.
 *
 * @package YiiBoilerplate\Actions
 */
class SimpleErrorAction extends CAction
{
    public function run()
    {
        $error = Yii::app()->getErrorHandler()->error;
        $status = Yii::app()->getRequest()->getPost('status');
        if(!$error && $status)
            $error = ['code' => $status, 'message' => 'Что-то пошло не так. Мы работаем над этим.'];

        if (!$error)
            return;

        if($layoutFile = $this->getController()->getLayoutFile('error/error'.$error['code']))
            $this->getController()->layout = 'error/error'.$error['code'];

        $view = 'error';
        if($this->getController()->getViewFile('error'.$error['code']))
            $view = 'error'.$error['code'];

        if (Yii::app()->getRequest()->isAjaxRequest) {
            Yii::app()->getAjax()->addReplace($view, '#content-replacement', $error)->send();
        } else {
            $this->getController()->render($view, $error);
        }
    }
}