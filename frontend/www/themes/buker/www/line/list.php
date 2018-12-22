<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 09.02.2015
 * Time: 23:23
 *
 * @var Sport[] $models
 * @var \frontend\components\FrontendController $this
 */ ?>

<div class="" id="content-replacement">
    <!--<table class="box-info">
        <colgroup width=""></colgroup>
        <tbody>
        <tr>
            <td class="box">
                <div class="wrp" style="height: auto;">
                </div>
            </td>
        </tr>
        </tbody>
    </table>-->
    <table class="table table-hover list-event">
        <tbody>
        <?php foreach ($models as $model): ?>
            <tr
                data-history="true"
                data-type="ajax"
                data-link="<?= Yii::app()->createUrl('/line/events', ['line_id' => $model->getId()]); ?>">
                <td class="sport-event-title">
                    <?= $model->getTitle(); ?>
                </td>
                <td style="width: 10px;">
                    <span class="badge"><?= $model->enableEventCount ?></span>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>