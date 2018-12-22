<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 10.02.2015
 * Time: 0:12
 *
 * @var \frontend\components\FrontendController $this
 * @var SportEvent[] $models
 * @var Sport $sport
 */ ?>
<div class="" id="content-replacement">
    <?php $this->widget('common\widgets\filter\FilterWidget', [
        'url' => Yii::app()->createUrl('/admin/event/all'),
        'clearUrl' => Yii::app()->createUrl('/admin/line/filter')
    ]) ?>
    <div class="head-title">
        <a
            class="label label-active back"
            data-type="ajax"
            data-history="true"
            data-link="<?= Yii::app()->getUser()->getState('return_link', Yii::app()->createUrl('/admin/line/events', ['line_id' => $sport->getId()])); ?>"
            href="javascript:void(0)">Вернуться</a>
        <?= $sport->getTitle(); ?>
    </div>
    <table class="table list-event">
        <colgroup span="1" width="2%"></colgroup>
        <colgroup span="1" width="5%"></colgroup>
        <colgroup span="1" width="15%"></colgroup>
        <colgroup span="3" width="5%"></colgroup>
        <colgroup span="3" width="5%"></colgroup>
        <colgroup span="3" width="5%"></colgroup>
        <colgroup span="3" width="5%"></colgroup>
        <colgroup span="2" width="5%"></colgroup>
        <thead>
        <tr class="odd">
            <th class="text-center">ID</th>
            <?php $this->renderPartial('eventView.' . $sport->getSportTypeView() . '.admin.head.' . $sport->getSportTemplate()) ?>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($models as $Event): ?>
            <tr>
                <td><?= $Event->getId() . '/' . $Event->getV() ?></td>
                <?php $this->renderPartial('eventView.' . $sport->getSportTypeView() . '.admin.body.' . $sport->getSportTemplate(), ['Event' => $Event]) ?>
                <td>
                    <span class="glyphicon glyphicon-time pointer"
                          title="<?= date('d.m.Y/H:i', $Event->getNewRatio()->getCreateAt()); ?>"
                          data-toggle="tooltip"></span>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>