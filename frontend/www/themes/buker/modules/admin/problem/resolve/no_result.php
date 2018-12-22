<?php
use \common\extensions\html\MHtml;

/**
 * Created by PhpStorm.
 *
 * @var SportEvent $Event
 * @var SportEventResult $EventResult
 * @var \common\components\Controller $this
 */ ?>

<div class="modal-dialog" id="replacement">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                    class="sr-only">Close</span></button>
            <h4 class="modal-title" id="myModalLabel">Рузельтат
                для <?= $Event->getTeam1() . ' - ' . $Event->getTeam2() ?></h4>
        </div>
        <?php /** @var TbActiveForm $form */
        $form = $this->beginWidget(
            '\common\widgets\booster\ActiveForm',
            [
                'id' => 'problem-no-result',
                'type' => 'horizontal',
                'htmlOptions' => [
                    'class' => 'ajax problem-no-result'
                ],
            ]
        ); ?>
        <div class="modal-body result-control">
            <?php $this->renderPartial('eventView.' . $Event->getEventTypeView() . '.admin.problem.no_result', ['Event' => $Event]) ?>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn label-active btn-sm sq" data-type="ajax-submit"
                    data-for="problem-no-result">Сохранить
            </button>
            <button type="button" class="btn label-none btn-sm sq" data-dismiss="modal">Отмена</button>
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div>