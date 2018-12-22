<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 09.02.2015
 * Time: 23:23
 *
 * @var Sport[] $sports
 * @var SportEvent[] $EventList
 * @var \common\extensions\pagination\Pagination $pages
 * @var \frontend\components\FrontendController $this
 */ ?>
<div class="" id="content-replacement">
    <div class="content-main">
        <div class="filter">
            <form data-history="true" method="get" class="center-block ajax"
                  action="<?= Yii::app()->createUrl('/line/all') ?>" id="all-betting-filter">
                <ul class="list-inline" style="text-align: center;">
                    <li>
                    <li>
                        <div class="input-daterange input-group datepicker" id="datepicker-filter">
                            <input type="text" class="input-sm form-control field" value="<?= $filter['start']; ?>"
                                   name="Filter[start]"/>
                            <span class="input-group-addon">-</span>
                            <input type="text" class="input-sm form-control col-xs-2 field" value="<?= $filter['end']; ?>"
                                   name="Filter[end]"/>
                        </div>
                    </li>
                    <li>
                        <a class="btn label-active btn-sm" data-history="true" id="filtered" data-for="all-betting-filter"
                           data-type="ajax-submit">Показать</a>
                        <a class="btn label-none btn-sm" href="<?= Yii::app()->createUrl('/line/all') ?>">Сбросить</a>
                    </li>
                </ul>
            </form>
        </div>
        <table class="table table-hover-n">
            <tbody id="all-event-block">
            <?php $this->renderPartial('page/all', ['sports' => $sports, 'EventList' => $EventList]) ?>
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