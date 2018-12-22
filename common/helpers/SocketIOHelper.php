<?php
namespace common\helpers;
use Yii;
/**
 * Created by PhpStorm.
 * User: me
 * Date: 21.08.2015
 * Time: 19:10
 */
class SocketIOHelper
{
    public static function eventChange($data = [])
    {
        $frame = Yii::app()->getNodeSocket()->getFrameFactory()->createEventFrame();
        $frame->setEventName('eventChange');
        foreach ($data as $key => $value)
            $frame[$key] = $value;

        $frame->send();
    }

    public static function eventTrash($ids = [])
    {
        if(empty($ids))
            return;

        $frame = Yii::app()->getNodeSocket()->getFrameFactory()->createEventFrame();
        $frame->setEventName('eventRemove2');
        $frame['event_ids'] = $ids;

        $frame->send();
    }

    public static function eventRemove($ids = [])
    {
        if(empty($ids))
            return;

        $frame = Yii::app()->getNodeSocket()->getFrameFactory()->createEventFrame();
        $frame->setEventName('eventRemove');
        $frame['event_ids'] = $ids;

        $frame->send();
    }
}