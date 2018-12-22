<?php
/**
 * Created by PhpStorm.
 * User: ice
 * Date: 16.09.2015
 * Time: 1:37
 *
 * @var \common\components\Controller $this
 * @var iSportEvent $Event
 */ ?>

<div id="event-recalc-body">
    <hr>
    <form class="ajax problem-no-result" action="<?= Yii::app()->createUrl('/admin/tools/changeResult') ?>" id="form-event-result">
        <input type="hidden" class="field" name="event_id" value="<?= $Event->getId() ?>">
        <?php $this->renderPartial('eventView.' . $Event->getEventTypeView() . '.admin.problem.no_result', ['Event' => $Event]) ?>
        <div class="row center">
            <input type="submit" class="btn label-active btn-xs" data-for="form-event-result" data-type="ajax-submit" value="Пересчитать">
            <button class="btn label-none btn-xs cancel">Отмена</button>
        </div>
        <div id="log"></div>
    </form>
</div>
