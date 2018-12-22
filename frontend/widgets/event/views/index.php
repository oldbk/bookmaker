<?php
/**
 * Created by PhpStorm.
 *
 * @var iSportEvent[] $EventList
 */ ?>

<?php foreach ($EventList as $model):
    if($model === false) continue;
    $DataToView = \common\factories\DataToViewFactory::factory($model->getSportType(), ['Event' => $model]);
    /** @var \common\sport\ratio\_interfaces\iRatioHomepage $NewRatio */
    $NewRatio = $model->getNewRatio();
    ?>
    <div class="event-preview-block">
        <div class="head-block">
            <div><?= $model->getSport()->getTitle() ?></div>
            <div><?= $model->getTeam1() ?> vs <?= $model->getTeam2() ?></div>
            <div><?= date('d.m H:i', $model->getDateInt()) ?></div>
        </div>
        <div class="body-block" id="bet-list">
            <table class="table" border="1">
                <colgroup>
                    <col>
                    <col width="50px">
                </colgroup>
                <tbody>
                <tr data-event="<?= $model->getId() ?>">
                    <td><?= $model->getTeam1() ?></td>
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
                </tr>
                <tr data-event="<?= $model->getId() ?>">
                    <td>Ничья</td>
                    <td class="center">
                        <div class="ratio">
                    <span
                        class="pointer c-ratio"
                        data-num="<?= $model->getNumber() ?>"
                        data-value="<?= $NewRatio->getRatioX() ?>"
                        data-type="<?= $model->getFieldAlias('ratio_x') ?>"
                        data-bet="true"><?= $DataToView->getRatioX() ?></span>
                            <?= $model->upDown('ratio_x'); ?>
                        </div>
                    </td>
                </tr>
                <tr class="no_border" data-event="<?= $model->getId() ?>">
                    <td><?= $model->getTeam2() ?></td>
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
                </tr>
                </tbody>
            </table>
        </div>
    </div>
<?php endforeach; ?>
