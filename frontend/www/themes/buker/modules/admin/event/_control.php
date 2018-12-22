<?php
use common\extensions\html\MHtml;

/**
 * Created by PhpStorm.
 * User: me
 * Date: 13.02.2015
 * Time: 18:36
 *
 * @var SportEvent $model
 * @var SportEventResult $result
 * @var Sport $sport
 * @var \frontend\components\FrontendController $this
 */ ?>

<div class="modal-dialog" id="replacement">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                    class="sr-only">Close</span></button>
            <h4 class="modal-title"
                id="myModalLabel"><?= $sport->getTitle() . '. ' . $model->getTeam1() . ' - ' . $model->getTeam2() ?></h4>
        </div>
        <?php /** @var TbActiveForm $form */
        $form = $this->beginWidget(
            '\common\widgets\booster\ActiveForm',
            [
                'id' => 'control-form',
                'type' => 'horizontal',
                'htmlOptions' => [
                    'class' => 'ajax'
                ],
            ]
        ); ?>
        <div class="modal-body result-control">
            <ul class="list-inline">
                <li style="width:265px;">
                    <table style="width:auto;">
                        <tbody>
                        <tr>
                            <td>
                                <label for="SportEvent_ratio_change_max_price">Коэффициент изменения лимита по
                                    ставке</label>
                                <?= CHtml::activeTextField($model, 'ratio_change_max_price', ['class' => 'form-control field double input-sm', 'style' => 'display:inline-block;margin-left:10px;']); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label
                                    for="SportEvent_is_freeze">Заморозить?</label> <?= MHtml::activeCheckBox($model, 'is_freeze', [
                                    'class' => 'field',
                                    'hiddenOptions' => ['class' => 'field']
                                ]); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?= CHtml::activeTextArea($model, 'admin_text', ['class' => 'form-control field', 'placeholder' => 'Введите комментарий', 'style' => 'display:inline-block;margin-left:10px;']); ?>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </li>
            </ul>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn label-active btn-sm sq" data-type="ajax-submit" data-for="control-form">
                Сохранить
            </button>
            <button type="button" class="btn label-none btn-sm sq" data-dismiss="modal">Отмена</button>
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div>