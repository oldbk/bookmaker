<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 13.02.2015
 * Time: 18:36
 *
 * @var TeamAliasNew $alias
 * @var Team[] $team
 * @var \frontend\components\FrontendController $this
 */ ?>

<div class="modal-dialog modal-sm" id="replacement">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                    class="sr-only">Close</span></button>
            <h4 class="modal-title" id="myModalLabel"><?= $alias->getTitle() ?></h4>
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
            <?= $form->checkboxGroup($alias, 'is_main', [
                'widgetOptions' => [
                    'htmlOptions' => ['class' => 'field']
                ]
            ]) ?>
            <?= $form->dropDownListGroup($alias, 'parent', [
                'widgetOptions' => [
                    'data' => CHtml::listData($team, 'id', 'title'),
                    'htmlOptions' => ['class' => 'field']
                ]
            ]) ?>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary sq" data-type="ajax-submit" data-for="control-form">Сохранить
            </button>
            <button type="button" class="btn btn-default sq" data-dismiss="modal">Отмена</button>
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div>