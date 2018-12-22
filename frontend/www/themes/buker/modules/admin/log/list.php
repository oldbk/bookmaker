<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 09.02.2015
 * Time: 23:23
 *
 * @var CActiveDataProvider $dataProvider
 * @var \frontend\components\FrontendController $this
 */ ?>

<div class="" id="content-replacement">
    <div class="filter">
        <form data-history="true" method="get" class="center-block ajax"
              action="<?= Yii::app()->createUrl('/admin/log/index') ?>" id="admin-log-filter">
            <ul class="list-inline">
                <li>
                    <input data-source-hidden-for="admin-log-user" placeholder="Начните вводить логин"
                           name="Filter[user_login]" value="<?= $filter['user_login'] ?>"
                           data-source="<?= Yii::app()->createUrl('/user/user/auto') ?>"
                           class="form-control input-sm field auto-source-user-list" type="text">
                    <input data-source-hidden="admin-log-user" type="hidden" name="Filter[user]"
                           value="<?= $filter['user'] ?>" class="field">
                </li>
                <li>
                    <div class="input-daterange input-group datepicker" id="datepicker-filter">
                        <input type="text" class="input-sm form-control field" value="<?= $filter['start']; ?>"
                               name="Filter[start]"/>
                        <span class="input-group-addon">-</span>
                        <input type="text" class="input-sm form-control col-xs-2 field" value="<?= $filter['end']; ?>"
                               name="Filter[end]"/>
                    </div>
                </li>
                <li>
                    <input type="submit" class="btn label-active btn-sm" data-history="true" id="filtered"
                           data-for="admin-log-filter" data-type="ajax-submit" value="Показать">
                    <a class="btn label-none btn-sm" href="<?= Yii::app()->createUrl('/admin/log/index') ?>">Сбросить</a>
                </li>
            </ul>
        </form>
    </div>
    <div>
        Найдено: <?= $dataProvider->getTotalItemCount(); ?>
    </div>
    <div class="grid-block">
        <?php $this->widget(
            'booster.widgets.TbGridView',
            [
                'id' => 'user-list',
                'dataProvider' => $dataProvider,
                'template' => "{pager}{items}{pager}",
                'itemsCssClass' => 'list',
                'pager' => [
                    'class' => 'common\extensions\pagination\Pagination'
                ],
                'ajaxUpdate' => false,
                'columns' => [
                    [
                        'name' => 'id',
                        'header' => '#',
                        'htmlOptions' => ['style' => 'width: 30px'],
                        'class' => 'common\extensions\grid\MDataColumn',
                    ],
                    [
                        'type' => 'raw',
                        'name' => 'login',
                        'value' => '$data->user->buildLogin()',
                        'htmlOptions' => ['nowrap' => 'true'],
                        'class' => 'common\extensions\grid\MDataColumn',
                    ],
                    [
                        'type' => 'raw',
                        'name' => 'description',
                        'value' => '$data->getFinalDescription()',
                        'class' => 'common\extensions\grid\MDataColumn',
                    ],
                    [
                        'name' => 'create_at',
                        'value' => 'date("d.m.Y H:i", $data->getCreateAt())',
                        'class' => 'common\extensions\grid\MDataColumn',
                    ],
                ],
            ]
        ); ?>
    </div>
</div>