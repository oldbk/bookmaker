<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 09.02.2015
 * Time: 23:23
 *
 * @var CActiveDataProvider $dataProvider
 * @var Akcii $model
 * @var TbActiveForm $form
 * @var AkciaForm $akciaForm
 * @var \frontend\components\FrontendController $this
 */ ?>

<div class="" id="content-replacement">
    <div id="akcia">
        <div class="row-line">
            <div class="column">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Добавить акцию</h3>
                    </div>
                    <div class="panel-body">
                        <?php $form = $this->beginWidget(
                            'booster.widgets.TbActiveForm',
                            [
                                'id' => 'add-akcia-form',
                                'action' => Yii::app()->createUrl('/admin/akcii/add'),
                                'type' => 'inline',
                                'htmlOptions' => ['class' => 'ajax']
                            ]
                        ); ?>
                        <?= $form->textFieldGroup(
                            $model,
                            'title',
                            [
                                'wrapperHtmlOptions' => ['class' => 'col-sm-9'],
                                'widgetOptions' => ['htmlOptions' => ['placeholder' => 'Название акции', 'class' => 'field']],
                            ]); ?>
                        <?php $this->widget(
                            'booster.widgets.TbButton',
                            [
                                'context' => 'primary',
                                'label' => 'Добавить',
                                'htmlOptions' => ['data-type' => 'ajax-submit', 'data-for' => 'add-akcia-form', 'data-loader' => 'true']
                            ]
                        ); ?>
                        <?php $this->endWidget(); ?>
                    </div>
                </div>
            </div>
            <div class="column-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Отправить средства по акции</h3>
                    </div>
                    <div class="panel-body">
                        <?php $form = $this->beginWidget(
                            'booster.widgets.TbActiveForm',
                            [
                                'id' => 'send-akcia-form',
                                'action' => Yii::app()->createUrl('/admin/akcii/send'),
                                'type' => 'inline',
                                'htmlOptions' => ['class' => 'ajax']
                            ]
                        ); ?>
                        <?= $form->dropDownListGroup(
                            $akciaForm,
                            'akcia_id',
                            [
                                'wrapperHtmlOptions' => ['class' => 'col-sm-9'],
                                'widgetOptions' => ['htmlOptions' => ['placeholder' => '', 'class' => 'field']],
                            ]); ?>
                        <?= $form->dropDownListGroup(
                            $akciaForm,
                            'user_id',
                            [
                                'wrapperHtmlOptions' => ['class' => 'col-sm-9'],
                                'widgetOptions' => ['htmlOptions' => ['placeholder' => '', 'class' => 'field']],
                            ]); ?>
                        <?= $form->dropDownListGroup(
                            $akciaForm,
                            'price_type',
                            [
                                'wrapperHtmlOptions' => ['class' => 'col-sm-9'],
                                'widgetOptions' => [
                                    'data' => \common\helpers\Convert::getPriceType(),
                                    'htmlOptions' => ['placeholder' => '', 'class' => 'field']
                                ],
                            ]); ?>
                        <?= $form->textFieldGroup(
                            $akciaForm,
                            'price',
                            [
                                'wrapperHtmlOptions' => ['class' => 'col-sm-3'],
                                'widgetOptions' => ['htmlOptions' => ['placeholder' => '', 'class' => 'field double', 'style' => 'width:70px;']],
                            ]); ?>
                        <?php $this->widget(
                            'booster.widgets.TbButton',
                            [
                                'context' => 'primary',
                                'label' => 'Добавить',
                                'htmlOptions' => ['data-type' => 'ajax-submit', 'data-for' => 'send-akcia-form', 'data-loader' => 'true']
                            ]
                        ); ?>
                        <?php $this->endWidget(); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="grid-block">
            <?php $this->widget(
                'booster.widgets.TbGridView',
                [
                    'id' => 'akcii-list',
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
                            'class' => 'common\extensions\grid\MDataColumn',
                        ],
                        [
                            'name' => 'create_at',
                            'value' => 'date("d.m.Y H:i", $data->getCreateAt())',
                            'class' => 'common\extensions\grid\MDataColumn',
                        ]
                    ],
                ]
            ); ?>
        </div>
    </div>
    <script>
        $(function () {
            select2Remote('#AkciaForm_akcia_id', '<?= Yii::app()->createUrl('/admin/akcii/auto'); ?>');
            select2Remote('#AkciaForm_user_id', '<?= Yii::app()->createUrl('/user/user/auto'); ?>');
        });
    </script>
</div>