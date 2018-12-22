<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 15.02.2015
 * Time: 2:56
 *
 * @var CPagination $pages
 * @var CActiveDataProvider $dataProvider
 * @var $this \frontend\modules\admin\components\AdminBaseController
 */ ?>
<div class="filter">
    <form
        data-history="true"
        method="get"
        class="center-block ajax"
        action="<?= Yii::app()->createUrl('/admin/user/index') ?>" id="user-list-filter">
        <ul class="list-inline">
            <li>
                <input data-source-hidden-for="user-list" placeholder="Начните вводить логин" name="Filter[user_login]"
                       value="<?= $filter['user_login'] ?>"
                       data-source="<?= Yii::app()->createUrl('/user/user/auto') ?>"
                       class="form-control input-sm field auto-source-user-list" type="text">
                <input data-source-hidden="user-list" type="hidden" name="Filter[user]" value="<?= $filter['user'] ?>"
                       class="field">
            </li>
            <li>
                <?= CHtml::dropDownList('Filter[is_blocked]', isset($filter['is_blocked']) ? $filter['is_blocked'] : '', CMap::mergeArray([-1 => 'Все'], [0 => 'Не в блоке', 1 => 'Заблокированные']), [
                    'class' => 'form-control col-xs-2 field input-sm',
                ]) ?>
            </li>
            <li>
                <input type="submit" class="btn label-active btn-sm" data-history="true" id="filtered"
                       data-for="user-list-filter" data-type="ajax-submit" value="Показать">
                <a class="btn label-none btn-sm" href="<?= Yii::app()->createUrl('/admin/user/index') ?>">Сбросить</a>
            </li>
        </ul>
    </form>
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
                    'value' => '$data->buildLogin()',
                    'htmlOptions' => ['nowrap' => 'true'],
                    'class' => 'common\extensions\grid\MDataColumn',
                ],
                [
                    'header' => 'а/б кр',
                    'name' => 'userActiveBalanceKR.active_diff',
                    //'value' => '$data->getActiveBalanceDiff(User::TYPE_KR)',
                    'class' => 'common\extensions\grid\MDataColumn',
                ],
                [
                    'header' => 'а/б екр',
                    'name' => 'userActiveBalanceEKR.active_diff',
                    //'value' => '$data->getActiveBalanceDiff(User::TYPE_EKR)',
                    'class' => 'common\extensions\grid\MDataColumn',
                ],
                /*[
                    'header' => 'а/б ваучеры',
                    'value' => '$data->getActiveBalanceDiff(User::TYPE_VOUCHER)'
                ],*/
                [
                    'header' => 'кр',
                    'name' => 'kr_balance',
                    'class' => 'common\extensions\grid\MDataColumn',
                ],
                [
                    'header' => 'екр',
                    'name' => 'ekr_balance',
                    'class' => 'common\extensions\grid\MDataColumn',
                ],
                [
                    'class' => 'booster.widgets.TbButtonColumn',
                    'htmlOptions' => ['nowrap' => 'nowrap'],
                    'buttons' => [
                        'edit' => [
                            'icon' => 'glyphicon glyphicon-pencil pointer',
                            'url' => 'Yii::app()->createUrl("/admin/user/update", ["user_id" => $data->getId()])',
                            'options' => [
                                'data-type' => 'ajax',
                                'data-toggle' => 'tooltip',
                                'title' => 'Редактировать'
                            ]
                        ],
                        'in' => [
                            'icon' => 'glyphicon glyphicon-arrow-down pointer',
                            'url' => 'Yii::app()->createUrl("/admin/user/in", ["user_id" => $data->getId()])',
                            'options' => [
                                'data-type' => 'ajax',
                                'data-toggle' => 'tooltip',
                                'title' => 'История ввода'
                            ]
                        ],
                        'out' => [
                            'icon' => 'glyphicon glyphicon-arrow-up pointer',
                            'url' => 'Yii::app()->createUrl("/admin/user/out", ["user_id" => $data->getId()])',
                            'options' => [
                                'data-type' => 'ajax',
                                'data-toggle' => 'tooltip',
                                'title' => 'История вывода'
                            ]
                        ],
                    ],
                    'template' => '{edit}{in}{out}'
                ]
            ],
        ]
    ); ?>
</div>