<?php
/**
 * Created by PhpStorm.
 * User: Ice
 * Date: 12.07.14
 * Time: 23:07
 */

use \common\components\VarDumper;
class MException
{
    const STATUS_CRITICAL = 7000;

    public static function ShowError($code, $message, $view = true)
    {
        if(Yii::app()->getRequest()->getIsAjaxRequest() && $view) {
            Yii::app()->getAjax()->addOther([
                'replaceList' => [
                    [
                        'view' => Yii::app()->getController()->renderPartial('common.themes.'.Yii::app()->getTheme()->name.'.ajax.error', [
                            'code' => $code,
                            'message' => $message
                        ], true),
                        'selector' => '#customModal #replacement'
                    ]
                ],
                'runJS' => ['name' => 'openCustom']
            ]);
        } else
            throw new CHttpException($code, $message);

        Yii::app()->getAjax()->send();
    }

    public static function log(Exception $ex, $logFile = 'log')
    {
        try {
            $date = date('d.m.Y H:i:s', time());
            ob_start();
            VarDumper::dump($ex);
            $text = "--------START ".$date."-------<br>\n";
            $text .= ob_get_clean();
            $text .= "--------END ".$date."-------<br>\n\n\n";
            $path = ROOT_DIR.'/logs/';
            $h = fopen($path.$logFile.".html","a");
            fwrite($h,$text);
            fclose($h);
        } catch (Exception $ex) {

        }

        \Yii::app()->getAjax()->addErrors('Возникли проблемы, попробуйте позже!');
    }

    public static function logTxt($text, $fileName = 'logTxt')
    {
        try {
            $date = date('d.m.Y H:i:s', time());
            ob_start();
            var_dump($text);
            $text = "--------START ".$date."-------<br>\n";
            $text .= ob_get_clean();
            $text .= "--------END ".$date."-------<br>\n\n\n";
            $path = ROOT_DIR.'/logs/';
            $h = fopen($path.$fileName.".html","a");
            fwrite($h,$text);
            fclose($h);
        } catch (Exception $ex) {

        }

        \Yii::app()->getAjax()->addErrors('Возникли проблемы, попробуйте позже!');
    }

    /**
     * @param Exception $ex
     * @param string $collection
     */
    public static function logMongo(Exception $ex, $collection = 'log')
    {
        //var_dump($ex->getMessage(). ' - '. $collection);
        if($ex->getCode() == -1 || !$ex->getMessage())
            return;
        $params = [];
        if($ex instanceof \common\components\NException) {
            $params = $ex->getParams();
        }

        $params['trace'] = [];
        foreach ($ex->getTrace() as $info) {
            $params['trace'][] = [
                'class' => isset($info['class']) ? $info['class'] : null,
                'method' => isset($info['function']) ? $info['function'] : null,
                'file' => isset($info['file']) ? $info['file'] : null,
                'line' => isset($info['line']) ? $info['line'] : null,
            ];
        }

        try {
            $params = [
                'code' => $ex->getCode(),
                'message' => $ex->getMessage(),
                'params' => $params,
                'date_int' => time(),
                'date_string' => date('d.m.Y H:i:s'),
            ];
            $params = unserialize(\ForceUTF8\Encoding::toUTF8(serialize($params)));
            Yii::app()->mongodb->{$collection}->insert($params);
        } catch (Exception $ex) {

        }
    }

    public static function mongoDebug($params, $collection = 'debug')
    {
        try {
            $params = unserialize(\ForceUTF8\Encoding::toUTF8(serialize($params)));
            Yii::app()->mongodb->{$collection}->insert($params);
        } catch (Exception $ex) {
        }
    }
}