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
    <div class="grid-block">
        <?php $this->widget(
            'booster.widgets.TbGridView',
            [
                'id' => 'critical-list',
                'dataProvider' => $dataProvider,
                'template' => "{pager}{items}{pager}",
                'itemsCssClass' => 'list',
                'pager' => [
                    'class' => 'common\extensions\pagination\Pagination'
                ],
                'columns' => [
                    [
                        'name' => 'type',
                    ],
                    [
                        'name' => 'message',
                    ],
                    [
                        'name' => 'file',
                        'value' => '$data->getFile()." (".$data->getLine().")"',
                    ],
                    [
                        'name' => 'updated_at',
                        'value' => 'date("d.m.Y H:i", $data->getUpdatedAt())'
                    ],
                    [
                        'name' => 'created_at',
                        'value' => 'date("d.m.Y H:i", $data->getCreatedAt())'
                    ],
                    [
                        'class' => 'booster.widgets.TbButtonColumn',
                        'htmlOptions' => ['nowrap' => 'nowrap'],
                        'buttons' => [
                            'notNew' => [
                                'icon' => 'glyphicon glyphicon-ok',
                                'url' => 'Yii::app()->createUrl("/admin/problem/critical_ok", ["critical_id" => $data->getId()])',
                                'options' => [
                                    'data-type' => 'ajax',
                                    'data-toggle' => 'tooltip',
                                    'title' => 'Просмотрено'
                                ],
                                'visible' => '$data->isNew()'
                            ],
                        ],
                        'template' => '{notNew}'
                    ]
                ],
            ]
        ); ?>
    </div>
</div>