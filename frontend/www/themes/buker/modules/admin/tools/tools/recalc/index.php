<?php
/**
 * Created by PhpStorm.
 * Date: 16.09.2015
 */ ?>

<div class="panel panel-default">
    <div class="panel-heading" data-toggle="collapse" data-target="#event-recalc">
        Пересчитать событие
        <div class="pull-right">
            <span class="caret"></span>
        </div>
    </div>
    <div class="panel-body collapse" id="event-recalc">
        <div class="row">
            <form class="ajax" action="<?= Yii::app()->createUrl('/admin/tools/result') ?>" id="form-event-id">
                <div class="col-md-5">
                    <input style="height: 30px;" placeholder="Введите номер события..." name="Recalc[event_id]" id="Recalc_event_id" class="form-control field">
                </div>
                <div class="col-md-3">
                    <input type="submit" class="btn label-active btn-sm" data-for="form-event-id" data-type="ajax-submit" value="Показать">
                </div>
            </form>
        </div>
        <div id="event-recalc-body"></div>
    </div>
</div>
