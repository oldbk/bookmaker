<?php

use common\extensions\html\MHtml;

/**
 * Created by PhpStorm.
 *
 * @var \frontend\components\FrontendController $this
 * @var PriceSettings $price
 * @var TbActiveForm $form
 *
 */ ?>

<div class="panel panel-default" id="settings-price-<?= $price->getPriceId() ?>">
    <div class="panel-heading">
        <h3 class="panel-title">Настройки <?= mb_strtoupper($price->getShortName()) ?></h3>
    </div>
    <div class="panel-body">
        <?php $form = $this->beginWidget(
            'booster.widgets.TbActiveForm',
            [
                'id' => 'settings-form-' . $price->getPriceId(),
                'type' => 'horizontal',
                'htmlOptions' => ['class' => 'ajax'],
                'action' => Yii::app()->createUrl('admin/settings/price', ['price_type' => $price->getPriceId()])
            ]
        ); ?>
        <?= $form->textFieldGroup(
            $price,
            'dop_ratio',
            [
                'wrapperHtmlOptions' => ['class' => 'col-sm-3', 'style' => 'width:40%'],
                'widgetOptions' => [
                    'htmlOptions' => [
                        'placeholder' => '',
                        'class' => 'field double',
                        'id' => 'PriceSettings_dop_ratio_' . $price->getPriceId(),
                    ]
                ],
                'labelOptions' => [
                    'class' => 'col-sm-3',
                    'style' => 'width:200px;',
                    'for' => 'PriceSettings_dop_ratio_' . $price->getPriceId(),
                ],
            ]); ?>
        <?= $form->textFieldGroup(
            $price,
            'event_limit',
            [
                'wrapperHtmlOptions' => ['class' => 'col-sm-3', 'style' => 'width:40%'],
                'widgetOptions' => [
                    'htmlOptions' => [
                        'placeholder' => '',
                        'class' => 'field double',
                        'id' => 'PriceSettings_event_limit_' . $price->getPriceId(),
                    ]
                ],
                'labelOptions' => [
                    'class' => 'col-sm-3',
                    'style' => 'width:200px;',
                    'for' => 'PriceSettings_event_limit_' . $price->getPriceId(),
                ],
            ]); ?>
        <?= $form->textFieldGroup(
            $price,
            'strange_output',
            [
                'wrapperHtmlOptions' => ['class' => 'col-sm-3', 'style' => 'width:40%'],
                'widgetOptions' => [
                    'htmlOptions' => [
                        'placeholder' => '',
                        'class' => 'field double',
                        'id' => 'PriceSettings_strange_output_' . $price->getPriceId(),
                    ]
                ],
                'labelOptions' => [
                    'class' => 'col-sm-3',
                    'style' => 'width:200px;',
                    'for' => 'PriceSettings_strange_output_' . $price->getPriceId(),
                ],
            ]); ?>
        <?= $form->textFieldGroup(
            $price,
            'min_bet',
            [
                'wrapperHtmlOptions' => ['class' => 'col-sm-3', 'style' => 'width:40%'],
                'widgetOptions' => [
                    'htmlOptions' => [
                        'placeholder' => '',
                        'class' => 'field double',
                        'id' => 'PriceSettings_min_bet_' . $price->getPriceId(),
                    ]
                ],
                'labelOptions' => [
                    'class' => 'col-sm-3',
                    'style' => 'width:200px;',
                    'for' => 'PriceSettings_min_bet_' . $price->getPriceId(),
                ],
            ]); ?>
        <?= $form->textFieldGroup(
            $price,
            'short_name',
            [
                'wrapperHtmlOptions' => ['class' => 'col-sm-3', 'style' => 'width:40%'],
                'widgetOptions' => [
                    'htmlOptions' => [
                        'placeholder' => '',
                        'class' => 'field',
                        'id' => 'PriceSettings_short_name_' . $price->getPriceId(),
                    ]
                ],
                'labelOptions' => [
                    'class' => 'col-sm-3',
                    'style' => 'width:200px;',
                    'for' => 'PriceSettings_short_name_' . $price->getPriceId(),
                ],
            ]); ?>
        <?= $form->textFieldGroup(
            $price,
            'max_ratio',
            [
                'wrapperHtmlOptions' => ['class' => 'col-sm-3', 'style' => 'width:40%'],
                'widgetOptions' => [
                    'htmlOptions' => [
                        'placeholder' => '',
                        'class' => 'field double',
                        'id' => 'PriceSettings_max_ratio_' . $price->getPriceId(),
                    ]
                ],
                'labelOptions' => [
                    'class' => 'col-sm-3',
                    'style' => 'width:200px;',
                    'for' => 'PriceSettings_max_ratio_' . $price->getPriceId(),
                ],
            ]); ?>
        <div class="checkbox" style="padding: 0;">
            <?= MHtml::activeCheckBox($price, 'auto_output', [
                'class' => 'field',
                'hiddenOptions' => ['class' => 'field'],
                'style' => 'margin: 0;margin-top: 1px;',
                'id' => 'PriceSettings_auto_output_' . $price->getPriceId(),
            ]); ?>
            <?= MHtml::activeLabel($price, 'auto_output', [
                'for' => 'PriceSettings_auto_output_' . $price->getPriceId(),
            ]) ?>
        </div>
        <?php $this->widget(
            'booster.widgets.TbButton',
            [
                'context' => 'primary',
                'label' => 'Сохранить',
                'htmlOptions' => ['data-type' => 'ajax-submit', 'data-for' => 'settings-form-' . $price->getPriceId()]
            ]
        ); ?>
        <?php $this->endWidget(); ?>
    </div>
</div>