<?php

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
<?php foreach ($models as $sport): ?>
    <div class="head-title"><?= $sport->getTitle(); ?></div>
    <table data-type="event-line" class="table table-hover list-event mass-block">
        <thead>
        <tr>
            <th>
                <input data-selector=".mass-buttons-event" class="mass-all-select" type="checkbox">
            </th>
            <th></th>
            <?php $this->renderPartial('eventView.' . $sport->getSportTypeView() . '.admin.head.' . $sport->getSportTemplate()) ?>
            <th style="width: 50px;"></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($EventList[$sport->getId()] as $Event): ?>
            <?php $this->renderPartial('ajax/_all', ['Event' => $Event]) ?>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endforeach; ?>