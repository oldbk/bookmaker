<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 10.02.2015
 * Time: 0:12
 *
 * @var \frontend\components\FrontendController $this
 * @var float $percent
 * @var float $price_commission
 * @var BettingGroup $BettingGroup
 *
 */ ?>
<div class="modal-dialog modal-sm" id="replacement">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                    class="sr-only">Close</span></button>
            <h4 class="modal-title" id="myModalLabel"><?= Yii::t('labels', 'Отмена ставки'); ?></h4>
        </div>
        <?php /** @var TbActiveForm $form */
        $form = $this->beginWidget(
            '\common\widgets\booster\ActiveForm',
            [
                'id' => 'refund-form',
                'type' => 'horizontal',
                'htmlOptions' => [
                    'class' => 'ajax'
                ],
            ]
        ); ?>
        <div class="modal-body">
            <div class="">Ставка: <?= \common\helpers\Convert::getMoneyFormat($BettingGroup->getPrice()); ?></div>
            <div class="">Проценты: <?= $percent ?>%</div>
            <div class="">Комиссия: <?= $price_commission ?></div>
            <input type="hidden" class="field" value="<?= $percent ?>" name="Refund[percent]">
            <input type="hidden" class="field" value="<?= $price_commission ?>" name="Refund[commission]">
        </div>
        <div class="modal-footer">
            <button type="button" class="btn label-none btn-sm sq" data-dismiss="modal">Отмена</button>
            <button type="button" data-modal-selector="#customModal" class="btn label-active btn-sm sq"
                    data-type="ajax-submit" data-for="refund-form">Сделать это
            </button>
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div>