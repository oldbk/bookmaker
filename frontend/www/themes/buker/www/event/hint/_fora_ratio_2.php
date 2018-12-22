<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 13.02.2015
 * Time: 18:36
 *
 * @var iSportEvent $model
 * @var array $hint
 * @var \frontend\components\FrontendController $this
 */
$DataToView = \common\factories\DataToViewFactory::factory($model->getSportType(), ['Event' => $model]);
?>

<div class="modal-dialog" id="replacement">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                    class="sr-only">Close</span></button>
            <h4 class="modal-title" id="myModalLabel"><?= $model->getTeam1() . ' - ' . $model->getTeam2() ?>
                Фора <?= $DataToView->getForaVal2() ?></h4>
        </div>
        <div class="modal-body result-control">
            <ul>
                <?php foreach ($hint as $text): ?>
                    <li>
                        <?= $text; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn label-none btn-sm sq" data-dismiss="modal">Закрыть</button>
        </div>
    </div>
</div>