<?php
/**
 * Created by PhpStorm.
 *
 * @var SportEvent $model
 */ ?>

<div class="modal-dialog" id="replacement">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                    class="sr-only">Close</span></button>
            <h4 class="modal-title" id="myModalLabel"><?= $model->getTeam1() . ' - ' . $model->getTeam2() ?></h4>
        </div>
        <div class="modal-body result-control">
            <div><strong>П1</strong> - победит <strong><?= $model->getTeam1() ?></strong></div>
            <div><strong>П2</strong> - победит <strong><?= $model->getTeam2() ?></strong></div>
            <div><strong>Х</strong> - ничья</div>
            <div><i>Ставки на футбольные матчи принимаются на основное время (90 мин). Время, добавленное судьей к
                    основному времени, является компенсированным временем (не путать с дополнительным временем "2 тайма
                    по 15 минут", которое назначается в кубковых матчах, серией пенальти или серией матчей и НЕ
                    учитывается).</i></div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn label-none btn-sm sq" data-dismiss="modal">Закрыть</button>
        </div>
    </div>
</div>