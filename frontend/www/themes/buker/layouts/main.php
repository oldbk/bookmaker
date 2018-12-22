<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 16.12.2014
 * Time: 16:13
 *
 * @var string $content
 * @var \common\components\Controller $this
 */ ?>
<?php $this->beginContent('frontend.www.themes.' . Yii::app()->theme->name . '.layouts.template'); ?>
    <div class="container">
        <div id="content">
            <?= $content; ?>
        </div>
        <!-- .content -->
    </div><!-- .container-->
    <div class="left-sidebar">
        <div id="uMenu">
            <?php $this->widget('\common\widgets\menu\MenuWidget', [
                'items' => $this->menu()->getAdminMenu(),
                'view' => 'uMenu'
            ]); ?>
        </div>
        <div id="sportMenu">
            <?php $this->widget('\common\widgets\menu\MenuWidget', [
                'items' => $this->menu()->getSportMenu(),
                'view' => 'sportMenu'
            ]); ?>
        </div>
        <div id="mainMenu">
            <?php $this->widget('\common\widgets\menu\MenuWidget', [
                'items' => $this->menu()->getMainMenu(),
                'view' => 'mainMenu'
            ]); ?>
        </div>
    </div><!-- .left-sidebar -->
<?php $this->endContent(); ?>