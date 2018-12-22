<?php
use common\interfaces\iStatus;

/**
 * Created by PhpStorm.
 * User: me
 * Date: 10.02.2015
 * Time: 0:12
 *
 * @var \frontend\components\FrontendController $this
 * @var Sport $sport
 * @var SportEvent[] $models
 *
 */ ?>
<div class="" id="content-replacement">
    <?php $this->widget('common\widgets\filter\FilterWidget', [
        'url' => Yii::app()->createUrl('/admin/event/all'),
        'clearUrl' => Yii::app()->createUrl('/admin/line/filter')
    ]) ?>
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
                <?php foreach ($models as $Event): ?>
                    <tr data-event="<?= $Event->getId() ?>">
                        <td style="padding: 2px;vertical-align: middle;">
                            <input data-selector=".mass-buttons-event" class="mass-select field" type="checkbox"
                                   name="selected[]" value="<?= $Event->getId(); ?>">
                        </td>
                        <td>
                            <?= CHtml::link('№' . $Event->getId(), Yii::app()->createUrl('/admin/event/all-betting', ['Filter[event_id]' => $Event->getId()]), ['data-type' => 'ajax', 'data-history' => 'true']) ?>
                        </td>
                        <?php $this->renderPartial('eventView.' . $Event->getEventTypeView() . '.admin.body.' . $sport->getSportTemplate(), ['Event' => $Event]) ?>
                        <td class="buttons" style="padding: 2px;vertical-align: middle;">
                            <?php $this->widget('common\widgets\buttons\ButtonsWidget', ['buttons' => [
                                'accept' => [
                                    'visible' => !in_array($Event->getStatus(), [iStatus::STATUS_FINISH, iStatus::STATUS_ENABLE]) && $Event->getDateInt() > time(),
                                    'htmlOptions' => [
                                        'data-type' => 'ajax',
                                        'data-link' => Yii::app()->createUrl('/admin/event/accept', ['event_id' => $Event->getId()]),
                                        //'data-confirm' => 'true',
                                        //'data-confirm-text' => 'Вы уверены, что хотите включить это событие?',
                                        'data-toggle' => 'tooltip',
                                        'title' => 'Включить',
                                        'class' => 'glyphicon glyphicon-ok pointer green'
                                    ]
                                ],
                                'decline' => [
                                    'visible' => !in_array($Event->getStatus(), [iStatus::STATUS_FINISH, iStatus::STATUS_DISABLE]) && $Event->getDateInt() > time(),
                                    'htmlOptions' => [
                                        'data-type' => 'ajax',
                                        'data-link' => Yii::app()->createUrl('/admin/event/decline', ['event_id' => $Event->getId()]),
                                        //'data-confirm' => 'true',
                                        //'data-confirm-text' => 'Вы уверены, что хотите выключить это событие?',
                                        'data-toggle' => 'tooltip',
                                        'title' => 'Выключить',
                                        'class' => 'glyphicon glyphicon-remove pointer red'
                                    ]
                                ],
                                'history' => [
                                    'htmlOptions' => [
                                        'data-history' => 'true',
                                        'data-type' => 'ajax',
                                        'data-link' => Yii::app()->createUrl('/admin/event/history', ['event_id' => $Event->getId(), 'line_id' => $Event->getSportId()]),
                                        'data-toggle' => 'tooltip',
                                        'title' => 'История изменений коэфициентов',
                                        'class' => 'glyphicon glyphicon-list-alt pointer'
                                    ]
                                ],
                                'control' => [
                                    'visible' => $Event->getStatus() != iStatus::STATUS_FINISH,
                                    'htmlOptions' => [
                                        'data-type' => 'ajax',
                                        'data-link' => Yii::app()->createUrl('/admin/event/control', ['event_id' => $Event->getId(), 'line_id' => $Event->getSportId()]),
                                        'class' => 'glyphicon glyphicon-cog pointer',
                                        'data-toggle' => 'tooltip',
                                        'title' => 'Управление событием'
                                    ]
                                ],
                                'rate_list' => [
                                    'visible' => $Event->cnt,
                                    'htmlOptions' => [
                                        'data-history' => 'true',
                                        'data-type' => 'ajax',
                                        'data-link' => Yii::app()->createUrl('/admin/event/bet', ['event_id' => $Event->getId(), 'line_id' => $Event->getSportId()]),
                                        'class' => 'glyphicon glyphicon-tasks pointer',
                                        'data-toggle' => 'tooltip',
                                        'title' => 'Посмотреть ставки с этим событием'
                                    ]
                                ],
                                'trash' => [
                                    'visible' => $Event->getStatus() == iStatus::STATUS_FINISH,
                                    'htmlOptions' => [
                                        'data-type' => 'ajax',
                                        'data-link' => Yii::app()->createUrl('/admin/event/trash', ['event_id' => $Event->getId(), 'line_id' => $Event->getSportId()]),
                                        'class' => 'glyphicon glyphicon-trash pointer',
                                        'data-toggle' => 'tooltip',
                                        //'data-confirm' => 'true',
                                        //'data-confirm-text' => 'Вы уверены, что хотите удалить событие в корзину?',
                                        'title' => 'Удалить событие в корзину'
                                    ]
                                ]
                            ]]) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
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
    </div>
</div>