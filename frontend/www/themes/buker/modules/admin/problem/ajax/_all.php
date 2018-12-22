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
            'resolve' => [
                'htmlOptions' => [
                    'data-type' => 'ajax',
                    'data-link' => Yii::app()->createUrl('/admin/event/resolve', ['event_id' => $Event->getId()]),
                    //'data-confirm' => 'true',
                    //'data-confirm-text' => 'Вы уверены, что хотите включить это событие?',
                    'data-toggle' => 'tooltip',
                    'title' => 'Удалить все проблемы',
                    'class' => 'glyphicon glyphicon-ok pointer green'
                ]
            ],
        ]]) ?>
    </td>
</tr>
