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
<?php unset($DataToView); ?>