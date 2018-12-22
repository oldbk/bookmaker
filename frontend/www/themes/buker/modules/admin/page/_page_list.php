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

<div class="grid-block">
    <?php $this->widget(
        'booster.widgets.TbGridView',
        [
            'id' => 'page-list',
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
                    'name' => 'title',
                ],
                [
                    'class' => 'booster.widgets.TbButtonColumn',
                    'htmlOptions' => ['nowrap' => 'nowrap'],
                    'template' => '{update}'
                ]
            ],
        ]
    ); ?>
</div>