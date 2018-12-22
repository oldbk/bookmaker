<?php
/**
 * Created by PhpStorm.
 * Date: 16.09.2015
 */ ?>

<div class="panel panel-default">
    <div class="panel-heading" data-toggle="collapse" data-target="#event-simulator">
        Симулятор ставок
        <div class="pull-right">
            <span class="caret"></span>
        </div>
    </div>
    <div class="panel-body collapse in" id="event-simulator">
        <div class="row">
            <form class="ajax" action="<?= Yii::app()->createUrl('/admin/tools/simulator') ?>" id="form-event-simulator">
                <div class="col-md-5">
                    <input style="height: 30px;" placeholder="Введите номер ставки..." name="Simulator[bet_id]" id="Simulator_bet_id" class="form-control field">
                </div>
                <div class="col-md-3">
                    <input type="submit" class="btn label-active btn-sm" data-for="form-event-simulator" data-type="ajax-submit" value="Показать">
                </div>
            </form>
        </div>
        <div id="event-simulator-body"></div>
    </div>
</div>
