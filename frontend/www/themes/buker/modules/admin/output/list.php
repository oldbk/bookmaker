<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 09.02.2015
 * Time: 23:23
 *
 * @var CActiveDataProvider $dataProvider
 * @var \frontend\components\FrontendController $this
 * @var TeamAliasNew[] $newAliases
 */ ?>

<div class="" id="content-replacement">
    <div class="grid-block">
        <?php $this->widget(
            'booster.widgets.TbGridView',
            [
                'id' => 'output-list',
                'dataProvider' => $dataProvider,
                'template' => "{pager}{items}{pager}",
                'itemsCssClass' => 'list',
                'pager' => [
                    'class' => 'common\extensions\pagination\Pagination'
                ],
                'columns' => [
                    [
                        'name' => 'id',
                        'header' => '#',
                        'htmlOptions' => ['style' => 'width: 30px'],
                        'class' => 'common\extensions\grid\MDataColumn',
                    ],
                    [
                        'name' => 'user_id',
                        'type' => 'raw',
                        'value' => '$data->user->buildLogin()." <span data-history=\"true\" class=\"pointer\" data-type=\"ajax\" data-link=\"".Yii::app()->createUrl("/admin/event/all-betting", ["Filter[user]" => $data->user->getId()])."\">[история ставок]</span>"',
                        'class' => 'common\extensions\grid\MDataColumn',
                    ],
                    [
                        'name' => 'price',
                        'value' => '$data->getPrice()." ".\common\helpers\Convert::getBalanceLabel($data->getPriceType())',
                        'htmlOptions' => ['nowrap' => 'true'],
                        'class' => 'common\extensions\grid\MDataColumn',
                    ],
                    [
                        'name' => 'create_at',
                        'value' => 'date("d.m.Y H:i", $data->getCreateAt())',
                        'class' => 'common\extensions\grid\MDataColumn',
                    ],
                    [
                        'class' => 'booster.widgets.TbButtonColumn',
                        'htmlOptions' => ['nowrap' => 'nowrap'],
                        'buttons' => [
                            'accept' => [
                                'icon' => 'glyphicon glyphicon-ok pointer green',
                                'url' => 'Yii::app()->createUrl("/admin/output/accept".$data->getPriceType(), ["request_id" => $data->getId()])',
                                'options' => [
                                    'data-type' => 'ajax',
                                    'data-toggle' => 'tooltip',
                                    'title' => 'Разрешить'
                                ]
                            ],
                            'decline' => [
                                'icon' => 'glyphicon glyphicon-remove pointer red',
                                'url' => 'Yii::app()->createUrl("/admin/output/decline".$data->getPriceType(), ["request_id" => $data->getId()])',
                                'options' => [
                                    'data-type' => 'ajax',
                                    'data-toggle' => 'tooltip',
                                    'title' => 'Запретить'
                                ]
                            ]
                        ],
                        'template' => '{accept}{decline}'
                    ]
                ],
            ]
        ); ?>
    </div>
</div>