<?php
/**
 * Created by PhpStorm.
 *
 * @var SportEvent $Event
 * @var \common\components\Controller $this
 */ ?>

<div class="modal-dialog modal-sm" id="replacement">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                    class="sr-only">Close</span></button>
            <h4 class="modal-title" id="myModalLabel">Дата
                для <?= $Event->getTeam1() . ' - ' . $Event->getTeam2() ?></h4>
        </div>
        <?php /** @var TbActiveForm $form */
        $form = $this->beginWidget(
            '\common\widgets\booster\ActiveForm',
            [
                'id' => 'problem-date',
                'type' => 'horizontal',
                'htmlOptions' => [
                    'class' => 'ajax'
                ],
            ]
        ); ?>
        <div class="modal-body">
            <?php $Event->setDateString(date('d/m H:i', $Event->getDateInt())); ?>
            <?= $form->textFieldGroup(
                $Event,
                'date_string',
                [
                    'wrapperHtmlOptions' => ['style' => 'width:100%;'],
                    'widgetOptions' => ['htmlOptions' => ['placeholder' => '', 'class' => 'field', 'name' => 'SportEvent[date_string]']],
                    'labelOptions' => ['label' => false]
                ]); ?>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn label-active btn-sm sq" data-type="ajax-submit" data-for="problem-date">
                Сохранить
            </button>
            <button type="button" class="btn label-none btn-sm sq" data-dismiss="modal">Отмена</button>
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div>