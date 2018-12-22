<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 10.02.2015
 * Time: 0:12
 *
 * @var \frontend\components\FrontendController $this
 * @var BettingGroup[] $models
 * @var Sport $sport
 * @var SportEvent $event
 * @var string $line_id
 * @var CPagination $pages
 *
 */ ?>
<div class="" id="content-replacement">
    <?php $this->widget('common\widgets\filter\FilterWidget', [
        'url' => Yii::app()->createUrl('/admin/line/events'),
        'clearUrl' => Yii::app()->createUrl('/admin/line/filter')
    ]) ?>
    <div class="head-title">
        <a
            class="label label-active back"
            data-type="ajax"
            data-history="true"
            data-link="<?= Yii::app()->getUser()->getState('return_link', Yii::app()->createUrl('/admin/line/events', ['line_id' => $line_id])); ?>"
            href="javascript:void(0)">Вернуться</a>
        <?= $sport->getTitle() . '. ' . $event->getTeam1() . ' - ' . $event->getTeam2(); ?>
    </div>
    <?php foreach ($models as $model): ?>
        <?php $this->renderPartial('_rate_item', [
            'historyList' => $model->userBetting,
            'BettingGroup' => $model,
            'event' => $event
        ]) ?>
    <?php endforeach; ?>
    <?php $this->widget('\common\extensions\pagination\Pagination', [
        'pages' => $pages,
    ]); ?>
</div>