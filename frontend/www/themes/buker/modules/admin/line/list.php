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
    <?php $this->widget('common\widgets\filter\FilterWidget', [
        'url' => Yii::app()->createUrl('/admin/event/all'),
        'clearUrl' => Yii::app()->createUrl('/admin/line/filter')
    ]) ?>
    <div class="line-block">
        <table class="table table-hover list-event">
            <tbody>
            <?php foreach ($models as $model): ?>
                <tr data-history="true"
                    data-type="ajax"
                    data-link="<?= Yii::app()->createUrl('/admin/line/events', ['line_id' => $model->getId()]); ?>">
                    <td class="sport-event-title">
                        <?= $model->getTitle(); ?>
                    </td>
                    <td class="time">
                        <div class="time">Последнее обновление: <?= $model->getUpdateAt('d.m.Y H:i') ?></div>
                    </td>
                    <td class="badge-block">
                        <?php if($model->newEventCount): ?>
                            <span class="badge green"><?= $model->newEventCount ?></span>
                        <?php endif; ?>
                    </td>
                    <td style="width: 10px;">
                        <span class="badge"><?= $model->allEventCount ?></span>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>