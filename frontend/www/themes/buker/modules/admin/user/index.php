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
    <div id="admin-part-user">
        <div class="columns-2">
            <div class="column">
                <?php $this->renderPartial('_user_list', ['dataProvider' => $dataProvider, 'filter' => $filter]); ?>
            </div>
            <div class="column loader-block column-2">
                <div class="page-dark-element">
                    <div class="page-loader-element"></div>
                </div>
                <div id="replace-info-block"></div>
            </div>
        </div>
    </div>
</div>