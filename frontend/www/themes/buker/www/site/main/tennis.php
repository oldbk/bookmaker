<?php
/**
 * Created by PhpStorm.
 * User: me
 *
 * @var TennisEvent[]  $Events
 */ ?>

<table data-type="event-line" class="table list head-sticker" id="bet-list">
    <colgroup>
        <col width="100px">
        <col>
        <col width="50px">
        <col width="50px">
        <col width="50px">
        <col width="11px">
    </colgroup>
    <thead>
    <tr class="head-title">
        <th colspan="2"></th>
        <th class="center strong">П1</th>
        <th class="center strong">П2</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($Events as $model):
        $DataToView = \common\factories\DataToViewFactory::factory($model->getSportType(), ['Event' => $model]);
        /** @var \common\sport\ratio\_interfaces\iRatioHomepage $NewRatio */
        $NewRatio = $model->getNewRatio();
        ?>
        <tr data-event="<?= $model->getId() ?>">
            <td>
                <?= date('d.m', $model->getDateInt()) ?><br><?= date('H:i', $model->getDateInt()) ?>
            </td>
            <td class="pointer" data-type="ajax"
                data-link="<?= Yii::app()->createUrl('/line/event', ['event_id' => $model->getId()]) ?>"
                data-history="true">
                <?= $model->getSport()->getTitle() ?><br>
                <?= $model->getTeam1() ?> - <?= $model->getTeam2() ?>
            </td>
            <td class="center">
                <div class="ratio">
                    <span
                        class="pointer c-ratio"
                        data-num="<?= $model->getNumber() ?>"
                        data-value="<?= $NewRatio->getRatioP1() ?>"
                        data-type="<?= $model->getFieldAlias('ratio_p1') ?>"
                        data-bet="true"><?= $DataToView->getRatioP1() ?></span>
                    <?= $model->upDown('ratio_p1'); ?>
                </div>
            </td>
            <td class="center">
                <div class="ratio">
                    <span
                        class="pointer c-ratio"
                        data-num="<?= $model->getNumber() ?>"
                        data-value="<?= $NewRatio->getRatioP2() ?>"
                        data-type="<?= $model->getFieldAlias('ratio_p2') ?>"
                        data-bet="true"><?= $DataToView->getRatioP2() ?></span>
                    <?= $model->upDown('ratio_p2'); ?>
                </div>
            </td>
            <td>
                    <span data-toggle="tooltip" data-title="Правила" data-type="ajax"
                          data-link="<?= Yii::app()->createUrl('/event/hint', ['event_id' => $model->getId(), 'field' => 'main_static']) ?>"
                          class="glyphicon glyphicon-info-sign pointer"></span>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
    <tfoot>
    <tr style="border: 0">
        <td colspan="17"></td>
    </tr>
    </tfoot>
</table>


