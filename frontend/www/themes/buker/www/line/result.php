<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 10.02.2015
 * Time: 0:12
 *
 * @var \frontend\components\FrontendController $this
 * @var Sport[] $sports
 * @var iSportEvent[] $events
 * @var iSportEvent $Event
 * @var string $datetime
 */ ?>
<div class="" id="content-replacement">
    <table class="box-info">
        <colgroup width=""></colgroup>
        <colgroup width="300"></colgroup>
        <tbody>
        <tr>
            <td class="box">
                <div class="wrp">
                    <h3>Результаты за <?= date('d.m.Y', strtotime($datetime)); ?></h3>
                    На этой странице Вы можете ознакомиться с результатами спортивных мероприятий.
                </div>
            </td>
            <td class="box">
                <div class="wrp">
                    <form method="post" id="result-form" action="<?= Yii::app()->createUrl('/line/result'); ?>"
                          class="ajax">
                        <ul class="list-inline">
                            <li>
                                <input value="<?= $datetime ?>" class="form-control datepicker field input-sm"
                                       type="text" autocomplete="off" name="datetime" id="datetime">
                            </li>
                            <li>
                                <button type="button" class="btn label-active btn-sm" data-type="ajax-submit"
                                        data-for="result-form">Показать
                                </button>
                            </li>
                        </ul>
                    </form>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
    <table class="table table-head-body" id="result-list" style="width: 100%;">
        <colgroup span="1" width="1%"></colgroup>
        <colgroup span="1" width="5%"></colgroup>
        <colgroup span="1" width="5%"></colgroup>
        <colgroup span="1" width="5%"></colgroup>
        <colgroup span="1" width="5%"></colgroup>
        <thead>
        </thead>
        <tbody>
        <?php if (count($sports) == 0): ?>
            <tr>
                <td class="center" colspan="5">Нет результатов</td>
            </tr>
        <?php endif; ?>
        <?php foreach ($sports as $sport): ?>
            <tr class="head-title">
                <td colspan="5"><?= $sport->getTitle() ?></td>
            </tr>
            <?php foreach ($events[$sport->getId()] as $Event): ?>
                <tr>
                    <td>
                        <?php if (Yii::app()->getUser()->isAdmin()): ?>
                            <?= CHtml::link('№' . $Event->getId(), Yii::app()->createUrl('/admin/event/all-betting', ['Filter[event_id]' => $Event->getId()]), ['data-type' => 'ajax', 'data-history' => 'true']) ?>
                        <?php else: ?>
                            №<?= $Event->getId(); ?>
                        <?php endif; ?>
                    </td>
                    <td><?= date('d.m.Y H:i', $Event->getDateInt()); ?></td>
                    <td><?= $Event->getTeam1(); ?></td>
                    <td><?= $Event->getTeam2(); ?></td>
                    <td>
                        <?= $Event->getResult()->getResultString() ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>