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

    <td>
    <span
        class="text-center editable"
        data-name="date"
        data-url="<?= Yii::app()->createUrl('/admin/event/date', ['event_id' => $Event->getId()]) ?>">
        <?= date('d/m H:i', $Event->getDateInt()); ?>
    </span>
    </td>
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
    <span class="fora">
        <span
            data-name="fora_val_1"
            data-url="<?= Yii::app()->createUrl('/admin/event/ratio', ['event_id' => $Event->getId()]) ?>"
            class="editable"><?= $DataToView->getForaVal1() ?></span>
    </span><br>
    <span class="fora">
        <span
            data-name="fora_val_2"
            data-url="<?= Yii::app()->createUrl('/admin/event/ratio', ['event_id' => $Event->getId()]) ?>"
            class="editable"><?= $DataToView->getForaVal2(); ?></span>
    </span>
    </td>
    <td>
        <div class="ratio">
        <span
            class="pointer editable"
            data-name="fora_ratio_1"
            data-url="<?= Yii::app()->createUrl('/admin/event/ratio', ['event_id' => $Event->getId()]); ?>">
            <?= $DataToView->getForaRatio1(); ?></span>
            <?= $Event->upDown('fora_ratio_1'); ?>
        </div>
        <div class="ratio">
        <span
            class="pointer editable"
            data-name="fora_ratio_2"
            data-url="<?= Yii::app()->createUrl('/admin/event/ratio', ['event_id' => $Event->getId()]); ?>">
            <?= $DataToView->getForaRatio2(); ?></span>
            <?= $Event->upDown('fora_ratio_2'); ?>
        </div>
    </td>
    <td>
        <?php if ($Event->getNewRatio()->isFora1Hint()): ?>
            <span data-type="ajax"
                  data-link="<?= Yii::app()->createUrl('/event/hint', ['event_id' => $Event->getId(), 'field' => 'fora_ratio_1']) ?>"
                  class="glyphicon glyphicon-question-sign pointer"></span>
        <?php endif; ?>
        <?php if ($Event->getNewRatio()->isFora2Hint()): ?>
            <br><span data-type="ajax"
                      data-link="<?= Yii::app()->createUrl('/event/hint', ['event_id' => $Event->getId(), 'field' => 'fora_ratio_2']) ?>"
                      class="glyphicon glyphicon-question-sign pointer"></span>
        <?php endif ?>
    </td>
    <td>
    <span
        class="pointer editable"
        data-name="total_val"
        data-url="<?= Yii::app()->createUrl('/admin/event/ratio', ['event_id' => $Event->getId()]) ?>"><?= $DataToView->getTotalVal(); ?></span>
    </td>
    <td class="text-center">
        <div class="ratio">
        <span
            class="pointer editable"
            data-name="total_more"
            data-url="<?= Yii::app()->createUrl('/admin/event/ratio', ['event_id' => $Event->getId()]); ?>">
            <?= $DataToView->getTotalMore(); ?></span>
            <?= $Event->upDown('total_more'); ?>
        </div>
    </td>
    <td class="text-center">
        <div class="ratio">
        <span
            class="pointer editable"
            data-name="total_less"
            data-url="<?= Yii::app()->createUrl('/admin/event/ratio', ['event_id' => $Event->getId()]); ?>">
            <?= $DataToView->getTotalLess(); ?></span>
            <?= $Event->upDown('total_less'); ?>
        </div>
    </td>
    <td>
        <div class="ratio">
        <span
            class="pointer editable"
            data-name="ratio_p1"
            data-url="<?= Yii::app()->createUrl('/admin/event/ratio', ['event_id' => $Event->getId()]); ?>">
            <?= $DataToView->getRatioP1() ?></span>
            <?= $Event->upDown('ratio_p1'); ?>
        </div>
    </td>
    <td>
        <div class="ratio">
        <span
            class="pointer editable"
            data-name="ratio_x"
            data-url="<?= Yii::app()->createUrl('/admin/event/ratio', ['event_id' => $Event->getId()]); ?>">
            <?= $DataToView->getRatioX() ?></span>
            <?= $Event->upDown('ratio_x'); ?>
        </div>
    </td>
    <td>
        <div class="ratio">
        <span
            class="pointer editable"
            data-name="ratio_p2"
            data-url="<?= Yii::app()->createUrl('/admin/event/ratio', ['event_id' => $Event->getId()]); ?>">
            <?= $DataToView->getRatioP2() ?></span>
            <?= $Event->upDown('ratio_p2'); ?>
        </div>
    </td>
    <td>
        <div class="ratio">
        <span
            class="pointer editable"
            data-name="ratio_1x"
            data-url="<?= Yii::app()->createUrl('/admin/event/ratio', ['event_id' => $Event->getId()]); ?>">
            <?= $DataToView->getRatio1x() ?></span>
            <?= $Event->upDown('ratio_1x'); ?>
        </div>
    </td>
    <td>
        <div class="ratio">
        <span
            class="pointer editable"
            data-name="ratio_12"
            data-url="<?= Yii::app()->createUrl('/admin/event/ratio', ['event_id' => $Event->getId()]); ?>">
            <?= $DataToView->getRatio12() ?></span>
            <?= $Event->upDown('ratio_12'); ?>
        </div>
    </td>
    <td>
        <div class="ratio">
        <span
            class="pointer editable"
            data-name="ratio_x2"
            data-url="<?= Yii::app()->createUrl('/admin/event/ratio', ['event_id' => $Event->getId()]); ?>">
            <?= $DataToView->getRatioX2() ?></span>
            <?= $Event->upDown('ratio_x2'); ?>
        </div>
    </td>
    <td class="center">
    <span
        data-name="itotal_val_1"
        data-url="<?= Yii::app()->createUrl('/admin/event/ratio', ['event_id' => $Event->getId()]) ?>"
        class="editable"><?= $DataToView->getITotalVal1(); ?></span><br>
    <span
        data-name="itotal_val_2"
        data-url="<?= Yii::app()->createUrl('/admin/event/ratio', ['event_id' => $Event->getId()]) ?>"
        class="editable"><?= $DataToView->getITotalVal2() ?></span>
    </td>
    <td>
        <div class="ratio">
        <span
            class="pointer editable"
            data-name="itotal_more_1"
            data-url="<?= Yii::app()->createUrl('/admin/event/ratio', ['event_id' => $Event->getId()]); ?>">
            <?= $DataToView->getITotalMore1(); ?></span>
            <?= $Event->upDown('itotal_more_1'); ?>
        </div>
        <div class="ratio">
        <span
            class="pointer editable"
            data-name="itotal_more_2"
            data-url="<?= Yii::app()->createUrl('/admin/event/ratio', ['event_id' => $Event->getId()]); ?>">
            <?= $DataToView->getITotalMore2(); ?></span>
            <?= $Event->upDown('itotal_more_2'); ?>
        </div>
    </td>
    <td>
        <div class="ratio">
        <span
            class="pointer editable"
            data-name="itotal_less_1"
            data-url="<?= Yii::app()->createUrl('/admin/event/ratio', ['event_id' => $Event->getId()]); ?>">
            <?= $DataToView->getITotalLess1(); ?></span>
            <?= $Event->upDown('itotal_less_1'); ?>
        </div>
        <div class="ratio">
        <span
            class="pointer editable"
            data-name="itotal_less_2"
            data-url="<?= Yii::app()->createUrl('/admin/event/ratio', ['event_id' => $Event->getId()]); ?>">
            <?= $DataToView->getITotalLess2(); ?></span>
            <?= $Event->upDown('itotal_less_2'); ?>
        </div>
    </td>
<?php unset($DataToView); ?>