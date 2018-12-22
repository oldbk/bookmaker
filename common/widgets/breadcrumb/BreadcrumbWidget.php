<?php
namespace common\widgets\breadcrumb;

use CWidget;
use Yii;
/**
 * Class MessageListWidget
 *
 * @package application.widgets.messageList
 */
class BreadcrumbWidget extends CWidget
{
    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $this->render('index', ['breadcrumbs' => Yii::app()->breadcrumbs->getBreadcrumbs()]);
    }
}