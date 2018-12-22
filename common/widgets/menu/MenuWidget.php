<?php
namespace common\widgets\menu;

use CWidget;
use Yii;
/**
 * Class MessageListWidget
 *
 * @package application.widgets.messageList
 */
class MenuWidget extends CWidget
{
    public $items = [];
    public $id = null;
    public $view = null;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $this->render($this->view, ['items' => $this->items, 'id' => $this->id]);
    }
}