<?php

use common\extensions\html\MHtml;

/**
 * Created by PhpStorm.
 *
 * @var \frontend\components\FrontendController $this
 * @var Settings $model
 * @var TbActiveForm $form
 */ ?>

<div class="panel panel-default" id="settings-price">
    <div class="panel-heading">
        <h3 class="panel-title">Настройки</h3>
    </div>
    <div class="panel-body">
        <?php $form = $this->beginWidget(
            'booster.widgets.TbActiveForm',
            [
                'id' => 'settings-form',
                'type' => 'horizontal',
                'htmlOptions' => ['class' => 'ajax'],
                'action' => Yii::app()->createUrl('admin/settings/settings')
            ]
        ); ?>
        <?= $form->textFieldGroup(
            $model,
            'min_time_decline',
            [
                'wrapperHtmlOptions' => ['class' => 'col-sm-4'],
                'widgetOptions' => ['htmlOptions' => ['placeholder' => '', 'class' => 'field numbers']],
                'append' => 'мин. <i class="glyphicon glyphicon-question-sign" data-toggle="tooltip" title="Минимальное допустимое время отказа"></i>'
            ]); ?>
        <?= $form->textFieldGroup(
            $model,
            'max_percent_decline',
            [
                'wrapperHtmlOptions' => ['class' => 'col-sm-4'],
                'widgetOptions' => ['htmlOptions' => ['placeholder' => '', 'class' => 'field double', 'data-max' => '100.00']],
                'append' => '% <i class="glyphicon glyphicon-question-sign" data-toggle="tooltip" title="Максимальный % коммисии при отказе"></i>'
            ]); ?>
        <div class="checkbox" style="  margin-left: 301px;padding: 0;">
            <?= MHtml::activeCheckBox($model, 'is_daily_limit', ['class' => 'field', 'hiddenOptions' => ['class' => 'field'], 'style' => 'margin: 0;margin-top: 1px;']); ?>
            <?= MHtml::activeLabel($model, 'is_daily_limit') ?>
        </div>
        <div class="checkbox" style="  margin-left: 301px;padding: 0;">
            <?= MHtml::activeCheckBox($model, 'enable_autoapprove', ['class' => 'field', 'hiddenOptions' => ['class' => 'field'], 'style' => 'margin: 0;margin-top: 1px;']); ?>
            <?= MHtml::activeLabel($model, 'enable_autoapprove') ?>
        </div>
        <?= $form->textFieldGroup(
            $model,
            'deadline_notify_event',
            [
                'wrapperHtmlOptions' => ['class' => 'col-sm-4'],
                'widgetOptions' => ['htmlOptions' => ['placeholder' => '', 'class' => 'field numbers']],
                'append' => 'час.'
            ]); ?>
        <?= $form->textFieldGroup(
            $model,
            'min_level',
            [
                'wrapperHtmlOptions' => ['class' => 'col-sm-4'],
                'widgetOptions' => ['htmlOptions' => ['placeholder' => '', 'class' => 'field numbers']],
            ]); ?>
        <?php $this->widget(
            'booster.widgets.TbButton',
            [
                'context' => 'primary',
                'label' => 'Сохранить',
                'htmlOptions' => ['data-type' => 'ajax-submit', 'data-for' => 'settings-form']
            ]
        ); ?>
        <?php $this->endWidget(); ?>
    </div>
</div>