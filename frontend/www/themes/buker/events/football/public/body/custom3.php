<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 10.02.2015
 * Time: 0:26
 *
 * @var FootballEvent $Event
 */

$DataToView = \common\factories\DataToViewFactory::factory($Event->getSportType(), ['Event' => $Event]);
?>

    <td class="center"><?= date('d/m H:i', $Event->getDateInt()); ?></td>
    <td data-type="ajax" data-link="<?= Yii::app()->createUrl('/line/event', ['event_id' => $Event->getId()]) ?>"
        data-history="true">
        <span data-team="1" class="pointer"><?= $Event->getTeam1(); ?></span><br>
        <span data-team="2" class="pointer"><?= $Event->getTeam2(); ?></span>
        <?php $text = $Event->getAdminText();
        if (strlen($text) > 0): ?>
            <hr>
            <em><?= $text ?></em>
        <?php endif; ?>
    </td>
    <td>
        <span><?= $DataToView->getTotalVal(); ?></span>
    </td>
    <td class="center">
        <div class="ratio">
        <span
            class="pointer c-ratio"
            data-num="<?= $Event->getNumber() ?>"
            data-value="<?= $Event->getNewRatio()->getTotalMore(); ?>"
            data-type="<?= $Event->getFieldAlias('total_more') ?>"
            data-bet="true"><?= $DataToView->getTotalMore(); ?></span>
            <?= $Event->upDown('total_more'); ?>
        </div>
        <?php if ($Event->getNewRatio()->isTotalHint()): ?>
            <span data-type="ajax"
                  data-link="<?= Yii::app()->createUrl('/event/hint', ['event_id' => $Event->getId(), 'field' => 'total_more']) ?>"
                  class="glyphicon glyphicon-question-sign pointer"></span>
        <?php endif ?>
    </td>
    <td class="center">
        <div class="ratio">
        <span
            class="pointer c-ratio"
            data-num="<?= $Event->getNumber() ?>"
            data-value="<?= $Event->getNewRatio()->getTotalLess(); ?>"
            data-type="<?= $Event->getFieldAlias('total_less') ?>"
            data-bet="true"><?= $DataToView->getTotalLess(); ?></span>
            <?= $Event->upDown('total_less'); ?>
        </div>
        <?php if ($Event->getNewRatio()->isTotalHint()): ?>
            <span data-type="ajax"
                  data-link="<?= Yii::app()->createUrl('/event/hint', ['event_id' => $Event->getId(), 'field' => 'total_less']) ?>"
                  class="glyphicon glyphicon-question-sign pointer"></span>
        <?php endif; ?>
    </td>
    <td>
        <div class="ratio">
        <span
            class="pointer c-ratio"
            data-num="<?= $Event->getNumber() ?>"
            data-value="<?= $Event->getNewRatio()->getRatioP1() ?>"
            data-type="<?= $Event->getFieldAlias('ratio_p1') ?>"
            data-bet="true"><?= $DataToView->getRatioP1() ?></span>
            <?= $Event->upDown('ratio_p1'); ?>
        </div>
    </td>
    <td>
        <div class="ratio">
        <span
            class="pointer c-ratio"
            data-num="<?= $Event->getNumber() ?>"
            data-value="<?= $Event->getNewRatio()->getRatioX() ?>"
            data-type="<?= $Event->getFieldAlias('ratio_x') ?>"
            data-bet="true"><?= $DataToView->getRatioX() ?></span>
            <?= $Event->upDown('ratio_x'); ?>
        </div>
    </td>
    <td>
        <div class="ratio">
        <span
            class="pointer c-ratio"
            data-num="<?= $Event->getNumber() ?>"
            data-value="<?= $Event->getNewRatio()->getRatioP2() ?>"
            data-type="<?= $Event->getFieldAlias('ratio_p2') ?>"
            data-bet="true"><?= $DataToView->getRatioP2() ?></span>
            <?= $Event->upDown('ratio_p2'); ?>
        </div>
    </td>
    <td>
        <div class="ratio">
        <span
            class="pointer c-ratio"
            data-num="<?= $Event->getNumber() ?>"
            data-value="<?= $Event->getNewRatio()->getRatio1x() ?>"
            data-type="<?= $Event->getFieldAlias('ratio_1x') ?>"
            data-bet="true"><?= $DataToView->getRatio1x() ?></span>
            <?= $Event->upDown('ratio_1x'); ?>
        </div>
    </td>
    <td>
        <div class="ratio">
        <span
            class="pointer c-ratio"
            data-num="<?= $Event->getNumber() ?>"
            data-value="<?= $Event->getNewRatio()->getRatio12() ?>"
            data-type="<?= $Event->getFieldAlias('ratio_12') ?>"
            data-bet="true"><?= $DataToView->getRatio12() ?></span>
            <?= $Event->upDown('ratio_12'); ?>
        </div>
    </td>
    <td>
        <div class="ratio">
        <span
            class="pointer c-ratio"
            data-num="<?= $Event->getNumber() ?>"
            data-value="<?= $Event->getNewRatio()->getRatioX2() ?>"
            data-type="<?= $Event->getFieldAlias('ratio_x2') ?>"
            data-bet="true"><?= $DataToView->getRatioX2() ?></span>
            <?= $Event->upDown('ratio_x2'); ?>
        </div>
    </td>
<?php unset($DataToView) ?>