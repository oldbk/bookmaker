<?php
use common\interfaces\iStatus;

/**
 * Created by PhpStorm.
 * User: me
 * Date: 10.02.2015
 * Time: 0:12
 *
 * @var \frontend\components\FrontendController $this
 * @var Sport[] $models
 * @var SportEvent[] $EventList
 * @var SportEvent $Event
 * @var array $filter
 * @var CPagination $pages
 *
 */ ?>
<div class="line-block">
    <form id="form-mass-select" class="ajax" method="post">
        <div class="buttons mass-buttons-event" style="margin-bottom: 10px;">
            <a data-link="<?= Yii::app()->createUrl('/admin/event/accept') ?>"
               class="btn label-active btn-sm disabled" data-for="form-mass-select" data-type="ajax-submit">включить</a>
            <a data-link="<?= Yii::app()->createUrl('/admin/event/decline') ?>"
               class="btn label-active btn-sm disabled" data-for="form-mass-select"
               data-type="ajax-submit">выключить</a>
            <a data-link="<?= Yii::app()->createUrl('/admin/event/trash') ?>"
               class="btn label-active btn-sm disabled" data-for="form-mass-select" data-type="ajax-submit">удалить</a>
        </div>
        <div id="page-line-block">
            <?php $this->renderPartial('page/all', ['EventList' => $EventList, 'models' => $models]); ?>
        </div>
        <div class="buttons mass-buttons-event" style="margin-top: 10px;">
            <a data-link="<?= Yii::app()->createUrl('/admin/event/accept') ?>"
               class="btn label-active btn-sm disabled" data-for="form-mass-select" data-type="ajax-submit">включить</a>
            <a data-link="<?= Yii::app()->createUrl('/admin/event/decline') ?>"
               class="btn label-active btn-sm disabled" data-for="form-mass-select"
               data-type="ajax-submit">выключить</a>
            <a data-link="<?= Yii::app()->createUrl('/admin/event/trash') ?>"
               class="btn label-active btn-sm disabled" data-for="form-mass-select" data-type="ajax-submit">удалить</a>
        </div>
    </form>
    <div class="pager-infinity" data-to="page-line-block" data-max="<?= $pages->getPageCount(); ?>">
        <?php $this->widget('\common\extensions\pagination\Pagination', [
            'pages' => $pages,
            'placeholder' => true
        ]); ?>
    </div>
</div>