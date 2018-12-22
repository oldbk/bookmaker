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
<div class="event-list" id="content-replacement">
    <table class="box-info">
        <colgroup width=""></colgroup>
        <colgroup width="300"></colgroup>
        <tbody>
        <tr>
            <td class="box">
                <div class="wrp">
                    <h3>Линия на <?= date('d.m.Y') ?></h3>
                    Просто "кликните" по соответствующему коэффициенту, для того чтоб выбрать исход.
                    Выбрав один или несколько исходов, нажмите на "Сделать ставку".
                    Указав сумму завершите создание ставки.
                </div>
            </td>
            <td class="box">
                <div class="wrp o-form">
                    <div style="margin-bottom: 5px">
                        <label>Выбрано исходов:</label>
                        <span class="value" id="o-count">0</span>
                    </div>
                    <div>
                        <label>Общий коэфф:</label>
                        <span class="value" id="o-ratio">0.00</span>
                    </div>
                    <div style="position: absolute;bottom: 10px;">
                        <a href="javascript:void(0);" class="label label-none" id="o-clear">Очистить</a>
                        <a
                            href="javascript:void(0);"
                            class="label label-active"
                            data-link="<?= Yii::app()->createUrl('/bet/prepare') ?>"
                            id="o-do">Сделать ставку</a>
                    </div>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
    <div class="head-title">
        <a class="label label-active back" data-history="true" data-link="<?= Yii::app()->createUrl('/line/'.\common\helpers\SportHelper::getByID($sport->getSportType())); ?>"
           data-type="ajax">Вернуться</a>
        <?= $sport->getTitle(); ?>
    </div>
    <table class="table list-event head-sticker" id="bet-list">
        <?php $this->renderPartial('eventView.' . $sport->getSportTypeView() . '.public.head.' . $sport->getSportTemplate(), ['models' => $models]) ?>
    </table>
</div>