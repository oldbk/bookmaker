<?php
namespace common\widgets\buttons;

use CWidget;
/**
 * Class MessageListWidget
 *
 * @package application.widgets.messageList
 */
class ButtonsWidget extends CWidget
{
    public $buttons;
    public $wrapTag = 'span';

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $placeholder = [
            'label' => '',
            'htmlOptions' => '',
        ];

        $btv = [];
        foreach ($this->buttons as $button) {
            if(isset($button['visible']) && !$button['visible'])
                continue;

            $btv[] = \CMap::mergeArray($placeholder, $button);
        }

        $this->render('index', ['buttons' => $btv, 'wrapTag' => $this->wrapTag]);
    }
}