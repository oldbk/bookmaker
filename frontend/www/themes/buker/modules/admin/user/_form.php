<?php
use common\extensions\html\MHtml;

/**
 * Created by PhpStorm.
 * User: me
 * Date: 15.02.2015
 * Time: 3:19
 *
 * @var TbActiveForm $form
 * @var User $model
 * @var \frontend\modules\admin\components\AdminBaseController $this
 */ ?>

<div id="replace-info-block">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><?= $model->buildLogin(); ?></h3>
        </div>
        <div class="panel-body">
            <?php $form = $this->beginWidget(
                'booster.widgets.TbActiveForm',
                ['id' => 'update-user-form', 'type' => 'horizontal', 'htmlOptions' => ['class' => 'ajax']]
            ); ?>
            <?= $form->textFieldGroup(
                $model,
                'login',
                [
                    'wrapperHtmlOptions' => ['class' => 'col-sm-9'],
                    'widgetOptions' => ['htmlOptions' => ['placeholder' => '', 'disabled' => true]],
                ]); ?>
            <?= $form->textFieldGroup(
                $model,
                'extra_ratio',
                [
                    'wrapperHtmlOptions' => ['class' => 'col-sm-9'],
                    'widgetOptions' => ['htmlOptions' => ['placeholder' => '', 'class' => 'field double']],
                ]); ?>
            <?= $form->textAreaGroup(
                $model,
                'admin_comment',
                [
                    'wrapperHtmlOptions' => ['class' => 'col-sm-9'],
                    'widgetOptions' => ['htmlOptions' => ['placeholder' => '', 'class' => 'field']],
                ]); ?>
            <div class="checkbox" style="padding: 0; margin-left: 96px;">
                <?= MHtml::activeCheckBox($model, 'is_blocked', [
                    'class' => 'field',
                    'hiddenOptions' => ['class' => 'field'],
                    'style' => 'margin: 0;margin-top: 1px;',
                ]); ?>
                <?= MHtml::activeLabel($model, 'is_blocked') ?>
            </div>
            <?php $this->widget(
                'booster.widgets.TbButton',
                [
                    'context' => 'primary',
                    'label' => 'Сохранить',
                    'htmlOptions' => ['data-type' => 'ajax-submit', 'data-for' => 'update-user-form', 'data-loader' => 'true']
                ]
            ); ?>
            <?php $this->widget(
                'booster.widgets.TbButton',
                [
                    'context' => 'default',
                    'label' => 'Отмена',
                    'htmlOptions' => ['onclick' => 'closeUserEdit()']
                ]
            ); ?>
            <?php $this->endWidget(); ?>
        </div>
    </div>
</div>