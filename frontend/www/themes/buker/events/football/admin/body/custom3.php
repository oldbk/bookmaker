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
<?php unset($DataToView); ?>