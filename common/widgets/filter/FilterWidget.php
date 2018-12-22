<?php
namespace common\widgets\filter;

use CWidget;
use Yii;
/**
 * Class MessageListWidget
 *
 * @package application.widgets.messageList
 */
class FilterWidget extends CWidget
{
    public $url;
    public $clearUrl;
    public $filter = [];

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $placeholder = [
            'sport_type' => [],
            'event_status' => [],
            'liga' => [],
            'start' => date('d.m.Y'),
            'end' => date('d.m.Y')
        ];

        $filter = $this->filter;
        if(empty($filter))
            $filter = \CMap::mergeArray($placeholder, Yii::app()->getRequest()->getParam('Filter', []));

        $this->render('index', ['url' => $this->url, 'filter' => $filter, 'clearUrl' => $this->clearUrl]);
    }
}