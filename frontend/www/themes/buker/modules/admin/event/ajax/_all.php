<?php
use common\interfaces\iStatus;
/**
 * Created by PhpStorm.
 * User: me
 * Date: 21.08.2015
 * Time: 18:59
 *
 * @var iSportEvent $Event
 * @var \frontend\modules\admin\components\AdminBaseController $this
 */ ?>
<tr data-event="<?= $Event->getId() ?>">
    <td style="padding: 2px;vertical-align: middle;">
        <input data-selector=".mass-buttons-event" class="mass-select field" type="checkbox"
               name="selected[]" value="<?= $Event->getId(); ?>">
    </td>
    <td>
        <?php if($Event->isNotAuto()): ?>
            <span class="glyphicon glyphicon-warning-sign red" data-toggle="tooltip" title="<?= $Event->getNotAutoReason() ?>"></span>
        <?php endif; ?>
        <?= CHtml::link('№' . $Event->getId(), Yii::app()->createUrl('/admin/event/all-betting', ['Filter[event_id]' => $Event->getId()]), ['data-type' => 'ajax', 'data-history' => 'true']) ?>
    </td>
    <?php $this->renderPartial('eventView.' . $Event->getEventTypeView() . '.admin.body.' . $Event->getEventTemplate(), ['Event' => $Event]) ?>
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
                'visible' => $Event->getCnt(),
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
