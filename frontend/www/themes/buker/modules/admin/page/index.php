<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 15.02.2015
 * Time: 2:55
 *
 * @var \frontend\modules\admin\components\AdminBaseController $this
 * @var CPagination $pages
 * @var User[] $models
 * @var CActiveDataProvider $dataProvider
 */ ?>

<div class="" id="content-replacement">
    <?php $this->renderPartial('_page_list', ['dataProvider' => $dataProvider]); ?>
</div>