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
<div class="" id="content-replacement">
    <div class="line-block">
        <div id="page-line-block">
            <?php $this->renderPartial('page/all', ['EventList' => $EventList, 'models' => $models]); ?>
        </div>
        <div class="pager-infinity" data-to="page-line-block" data-max="<?= $pages->getPageCount(); ?>">
            <?php $this->widget('\common\extensions\pagination\Pagination', [
                'pages' => $pages,
                'placeholder' => true
            ]); ?>
        </div>
    </div>
</div>