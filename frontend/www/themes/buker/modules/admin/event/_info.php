<?php
/**
 * Created by PhpStorm.
 *
 * @var \common\components\Controller $this
 * @var SportEvent $model
 */ ?>
<div class="modal-dialog modal-lg" id="replacement">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                    class="sr-only">Close</span></button>
            <h4 class="modal-title"
                id="myModalLabel"><?= $model->getTitle() . '. ' . $model->getTeam1() . ' - ' . $model->getTeam2() ?></h4>
        </div>
        <div class="modal-body result-control">
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
                    <?php $this->renderPartial('eventView.' . $model->getSport()->getSportTypeView() . '.admin.head.' . $model->getSport()->getSportTemplate()) ?>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?= $model->getId() . '/' . $model->getV() ?></td>
                    <?php $this->renderPartial('eventView.' . $model->getSport()->getSportTypeView() . '.admin.body.' . $model->getSport()->getSportTemplate(), ['Event' => $model]) ?>
                    <td>
                        <span class="glyphicon glyphicon-time pointer"
                              title="<?= date('d.m.Y/H:i', $model->getUpdateAt()) ?>" data-toggle="tooltip"></span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default sq" data-dismiss="modal">Отмена</button>
        </div>
    </div>
</div>