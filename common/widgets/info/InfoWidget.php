<?php
namespace common\widgets\info;

use CWidget;
use Yii;
/**
 * Class MessageListWidget
 *
 * @package application.widgets.messageList
 */
class InfoWidget extends CWidget
{
    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $placeholder = [
            'sport_count' => 0,
            'find_event' => 0,
            'create_event_count' => 0,
            'update_event_count' => 0,
            'problem_count' => 0,
            'time_start' => time(),
            'time_end' => 0,
            'spend_time' => 0,
            'running_event_count' => 0
        ];

        $info = Yii::app()->cache->get('event_update_info');
        if($info === false)
            return;
        $info = \CMap::mergeArray($placeholder, $info);

        $placeholder = [
            'status' => 'undefined',
            'time' => time(),
            'at' => time()
        ];

        $sport_cron = Yii::app()->cache->get('sport_index');
        if($sport_cron === false)
            $sport_cron = [];
        $sport_cron = \CMap::mergeArray($placeholder, $sport_cron);

        $result_cron = Yii::app()->cache->get('results_index');
        if($result_cron === false)
            $result_cron = [];
        $result_cron = \CMap::mergeArray($placeholder, $result_cron);

        $this->render('index', ['info' => $info, 'sport_cron' => $sport_cron, 'result_cron' => $result_cron]);
    }
}