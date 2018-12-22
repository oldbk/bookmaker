<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 15.02.2015
 * Time: 2:55
 *
 * @var \frontend\modules\admin\components\AdminBaseController $this
 * @var Pages $model
 */ ?>

<div class="" id="content-replacement">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Редактирование <?= $model->getTitle() ?></h3>
        </div>
        <div class="panel-body">
            <?php $form = $this->beginWidget(
                'booster.widgets.TbActiveForm',
                ['id' => 'update-page-form', 'type' => 'horizontal']
            ); ?>
            <?php $this->widget('\common\extensions\imperavi\ImperaviRedactorWidget', [
                // You can either use it for model attribute
                'model' => $model,
                'attribute' => 'text',
                'options' => [
                    'lang' => 'ru',
                    //'toolbar' => false,
                    'iframe' => true,
                    'convertImageLinks' => true
                ],
                'plugins' => [
                    'fontcolor' => [
                        'js' => ['fontcolor.js']
                    ],
                    'fontfamily' => [
                        'js' => ['fontfamily.js']
                    ],
                    'fontsize' => [
                        'js' => ['fontsize.js']
                    ],
                    'imagemanager' => [
                        'js' => ['imagemanager.js']
                    ],
                    'table' => [
                        'js' => ['table.js']
                    ],
                    'textdirection' => [
                        'js' => ['textdirection.js']
                    ],
                    'textexpander' => [
                        'js' => ['textexpander.js']
                    ]
                ]
            ]); ?>
            <?php $this->widget(
                'booster.widgets.TbButton',
                [
                    'context' => 'primary',
                    'label' => 'Сохранить',
                    'htmlOptions' => ['type' => 'submit']
                ]
            ); ?>
            <?php $this->endWidget(); ?>
        </div>
    </div>
</div>