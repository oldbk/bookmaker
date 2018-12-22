<?php
/**
 * Created by PhpStorm.
 * User: me
 *
 * @var Sport[] $Sports
 * @var FootballEvent[] $Events
 * @var \frontend\components\SportController $this
 * @var CPagination $pages
 */ ?>

<div class="" id="content-replacement">
    <div class="content-main">
        <table class="table table-hover-n">
            <tbody id="all-event-block">
            <?php $this->renderPartial('page/index', ['Sports' => $Sports, 'Events' => $Events]) ?>
            </tbody>
        </table>
        <div class="pager-infinity" data-to="all-event-block" data-max="<?= $pages->getPageCount() ?>">
            <?php $this->widget('\common\extensions\pagination\Pagination', [
                'pages' => $pages,
                'placeholder' => true
            ]); ?>
        </div>
    </div>
    <div class="content-side">
        <?php $this->widget('frontend\widgets\coupon\CouponWidget') ?>
        <?php $this->widget('frontend\widgets\event\EventWidget') ?>
    </div>
</div>