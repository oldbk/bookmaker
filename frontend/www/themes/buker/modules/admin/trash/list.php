<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 10.02.2015
 * Time: 0:12
 *
 * @var \frontend\components\FrontendController $this
 * @var Sport[] $sports
 * @var SportEvent[] $EventList
 * @var SportEvent $Event
 *
 */ ?>
<div class="" id="content-replacement">
    <?php foreach ($sports as $sport): ?>
        <div class="head-title"><?= $sport->getTitle(); ?></div>
        <table data-type="trash" class="table table-hover list-event">
            <thead>
            <tr>
                <?php $this->renderPartial('eventView.' . $sport->getSportTypeView() . '.admin.head.' . $sport->getSportTemplate()) ?>
                <th style="width: 50px;"></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($EventList[$sport->getId()] as $Event): ?>
                <tr>
                    <?php $this->renderPartial('eventView.' . $sport->getSportTypeView() . '.admin.body.' . $sport->getSportTemplate(), ['Event' => $Event]) ?>
                    <td class="buttons">
                        <span data-type="ajax"
                              data-link="<?= Yii::app()->createUrl('/admin/trash/recovery', ['event_id' => $Event->getId(), 'line_id' => $Event->getSportId()]) ?>"
                              class="glyphicon glyphicon-repeat pointer"
                              data-toggle="tooltip"
                              title="Восстановить событие из корзины"></span>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endforeach; ?>
</div>