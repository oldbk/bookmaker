<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 10.02.2015
 * Time: 0:12
 *
 * @var \frontend\components\FrontendController $this
 * @var iSportEvent[] $team1games
 * @var iSportEvent[] $team2games
 * @var FootballEvent $event
 */

$DataToView = \common\factories\DataToViewFactory::factory($event->getSportType(), ['Event' => $event]);
?>
<div class="event-list" id="content-replacement">
    <div class="content-main">
        <table class="table list">
            <colgroup>
                <col width="50%">
                <col width="50%">
            </colgroup>
            <tbody>
            <tr>
                <td class="center" colspan="2">
                    <?= $event->getTeam1(); ?>
                    V
                    <?= $event->getTeam2(); ?>
                </td>
            </tr>
            <tr>
                <td class="center" colspan="2" style="position: relative;">
                    <div style="position:absolute;left: 10px;text-decoration:underline;">
                        <?php if (Yii::app()->getUser()->isAdmin()): ?>
                            <?= CHtml::link('№' . $event->getId(), Yii::app()->createUrl('/admin/event/all-betting', ['Filter[event_id]' => $event->getId()]), ['data-type' => 'ajax', 'data-history' => 'true']) ?>
                        <?php else: ?>
                            №<?= $event->getId(); ?>
                        <?php endif; ?>
                    </div><?= \common\components\DateFormat::getDate('%a %d %B %H:%M', $event->getDateInt()) ?>
                </td>
            </tr>
            <tr>
                <td class="center">
                    Последние матчи
                </td>
                <td class="center">
                    Последние матчи
                </td>
            </tr>
            </tbody>
        </table>
        <div class="event-show-ratio" style="margin-top: 0">
            <div class="row-line">
                <div class="column">
                    <table class="table list">
                        <colgroup>
                            <col width="230px">
                            <col width="50px">
                            <col width="230px">
                        </colgroup>
                        <tbody>
                        <tr style="border: 0;">
                            <td style="padding: 0" colspan="3"></td>
                        </tr>
                        <?php foreach ($team1games as $_event): ?>
                            <tr class="no_border">
                                <td class="right">
                                    <?= $_event->getResult()->getTeam1Result() > $_event->getResult()->getTeam2Result() ? "<strong>{$_event->getTeam1()}</strong>" : $_event->getTeam1() ?>
                                    <b>
                                </td>
                                <td class="center"><?= $_event->getResult()->getTeam1Result() ?>
                                    - <?= $_event->getResult()->getTeam2Result() ?></td>
                                <td class="left"><?= $_event->getTeam2() ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="column-2">
                    <table class="table list">
                        <colgroup>
                            <col width="230px">
                            <col width="50px">
                            <col width="230px">
                        </colgroup>
                        <tbody>
                        <tr style="border: 0;">
                            <td style="padding: 0" colspan="3"></td>
                        </tr>
                        <?php foreach ($team2games as $_event): ?>
                            <tr class="no_border">
                                <td class="right"><?= $_event->getTeam1() ?></td>
                                <td class="center"><?= $_event->getResult()->getTeam1Result() ?>
                                    - <?= $_event->getResult()->getTeam2Result() ?></td>
                                <td>
                                    <?= $_event->getResult()->getTeam2Result() > $_event->getResult()->getTeam1Result() ? "<strong>{$_event->getTeam2()}</strong>" : $_event->getTeam2() ?>
                                    <b>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php if (Yii::app()->getUser()->isAdmin() && $event->hasRelated('ratioFixed') && $event->ratioFixed): ?>
            <table class="table list" style="margin: 15px auto">
                <thead>
                <tr>
                    <th><strong>Фиксированные коэффициенты</strong> (кликните для удаления)</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <?php foreach ($event->ratioFixed as $RatioFixed): ?>
                            <a data-type="ajax" data-confirm="true"
                               data-confirm-text="Вы уверены, что хотите удалить фиксированный коэффициент? "
                               data-link="<?= Yii::app()->createUrl('/admin/event/deleteRatio', ['event_id' => $event->getId(), 'ratio' => $RatioFixed->getRatioName()]) ?>"
                               href="javascript:void(0);" class="label label-warning">
                                <?= $DataToView->getResultLabel($RatioFixed->getRatioName()) ?>
                                : <?= $RatioFixed->getRatioValue() ?>
                            </a>
                        <?php endforeach; ?>
                    </td>
                </tr>
                </tbody>
            </table>
        <?php endif; ?>
        <div class="event-show-ratio">
            <div class="row-line">
                <div class="column">
                    <table class="table table-head-body" id="bet-list">
                        <colgroup>
                            <col>
                            <col width="50px">
                            <col width="50px">
                            <col width="50px">
                            <col width="11px">
                        </colgroup>
                        <thead>
                        <tr>
                            <th></th>
                            <th class="center strong">П1</th>
                            <th class="center strong">Х</th>
                            <th class="center strong">П2</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr data-event="<?= $event->getId() ?>">
                            <td>
                                <?= $event->getTeam1() ?> - <?= $event->getTeam2() ?>
                            </td>
                            <td class="center">
                                <div class="ratio">
                                    <span
                                        class="pointer c-ratio"
                                        data-num="<?= $event->getNumber() ?>"
                                        data-value="<?= $event->getNewRatio()->getRatioP1() ?>"
                                        data-type="<?= $event->getFieldAlias('ratio_p1') ?>"
                                        data-bet="true"><?= $DataToView->getRatioP1() ?></span>
                                    <?= $event->upDown('ratio_p1'); ?>
                                </div>
                            </td>
                            <td class="center">
                                <div class="ratio">
                                    <span
                                        class="pointer c-ratio"
                                        data-num="<?= $event->getNumber() ?>"
                                        data-value="<?= $event->getNewRatio()->getRatioX() ?>"
                                        data-type="<?= $event->getFieldAlias('ratio_x') ?>"
                                        data-bet="true"><?= $DataToView->getRatioX() ?></span>
                                    <?= $event->upDown('ratio_x'); ?>
                                </div>
                            </td>
                            <td class="center">
                                <div class="ratio">
                                    <span
                                        class="pointer c-ratio"
                                        data-num="<?= $event->getNumber() ?>"
                                        data-value="<?= $event->getNewRatio()->getRatioP2() ?>"
                                        data-type="<?= $event->getFieldAlias('ratio_p2') ?>"
                                        data-bet="true"><?= $DataToView->getRatioP2() ?></span>
                                    <?= $event->upDown('ratio_p2'); ?>
                                </div>
                            </td>
                            <td>
                            <span data-toggle="tooltip" data-title="Правила" data-type="ajax"
                                  data-link="<?= Yii::app()->createUrl('/event/hint', ['event_id' => $event->getId(), 'field' => 'main_static']) ?>"
                                  class="glyphicon glyphicon-info-sign pointer"></span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="column-2">
                    <table class="table table-head-body" id="bet-list">
                        <colgroup>
                            <col>
                            <col width="50px">
                            <col width="50px">
                            <col width="50px">
                        </colgroup>
                        <thead>
                        <tr>
                            <th></th>
                            <th class="center strong">1x</th>
                            <th class="center strong">12</th>
                            <th class="center strong">x2</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr data-event="<?= $event->getId() ?>">
                            <td>
                                <?= $event->getTeam1() ?> - <?= $event->getTeam2() ?>
                            </td>
                            <td class="center">
                                <div class="ratio">
                                    <span
                                        class="pointer c-ratio"
                                        data-num="<?= $event->getNumber() ?>"
                                        data-value="<?= $event->getNewRatio()->getRatio1x() ?>"
                                        data-type="<?= $event->getFieldAlias('ratio_1x') ?>"
                                        data-bet="true"><?= $DataToView->getRatio1x() ?></span>
                                    <?= $event->upDown('ratio_p1'); ?>
                                </div>
                            </td>
                            <td class="center">
                                <div class="ratio">
                                    <span
                                        class="pointer c-ratio"
                                        data-num="<?= $event->getNumber() ?>"
                                        data-value="<?= $event->getNewRatio()->getRatio12() ?>"
                                        data-type="<?= $event->getFieldAlias('ratio_12') ?>"
                                        data-bet="true"><?= $DataToView->getRatio12() ?></span>
                                    <?= $event->upDown('ratio_x'); ?>
                                </div>
                            </td>
                            <td class="center">
                                <div class="ratio">
                                    <span
                                        class="pointer c-ratio"
                                        data-num="<?= $event->getNumber() ?>"
                                        data-value="<?= $event->getNewRatio()->getRatioX2() ?>"
                                        data-type="<?= $event->getFieldAlias('ratio_x2') ?>"
                                        data-bet="true"><?= $DataToView->getRatioX2() ?></span>
                                    <?= $event->upDown('ratio_p2'); ?>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row-line">
                <div class="column">
                    <table class="table table-head-body" id="bet-list">
                        <colgroup>
                            <col>
                            <col width="50px">
                            <col width="50px">
                        </colgroup>
                        <thead>
                        <tr>
                            <th colspan="4" class="strong">Форы</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr data-event="<?= $event->getId() ?>">
                            <td>
                                <?= $event->getTeam1() ?>
                            </td>
                            <td class="right">
                                <?= $DataToView->getForaVal1() ?>
                            </td>
                            <td class="center">
                                <div class="ratio">
                                    <span
                                        class="pointer c-ratio"
                                        data-num="<?= $event->getNumber() ?>"
                                        data-value="<?= $event->getNewRatio()->getForaRatio1() ?>"
                                        data-type="<?= $event->getFieldAlias('fora_ratio_1') ?>"
                                        data-bet="true"><?= $DataToView->getForaRatio1() ?></span>
                                    <?= $event->upDown('fora_ratio_1'); ?>
                                </div>
                            </td>
                            <?php if ($event->getNewRatio()->isFora1Hint()): ?>
                                <td style="width: 11px">
                                <span data-type="ajax"
                                      data-link="<?= Yii::app()->createUrl('/event/hint', ['event_id' => $event->getId(), 'field' => 'fora_ratio_1']) ?>"
                                      class="glyphicon glyphicon-question-sign pointer"></span>
                                </td>
                            <?php endif; ?>
                        </tr>
                        <tr data-event="<?= $event->getId() ?>">
                            <td>
                                <?= $event->getTeam2() ?>
                            </td>
                            <td class="right">
                                <?= $DataToView->getForaVal2() ?>
                            </td>
                            <td class="center">
                                <div class="ratio">
                                    <span
                                        class="pointer c-ratio"
                                        data-num="<?= $event->getNumber() ?>"
                                        data-value="<?= $event->getNewRatio()->getForaRatio2() ?>"
                                        data-type="<?= $event->getFieldAlias('fora_ratio_2') ?>"
                                        data-bet="true"><?= $DataToView->getForaRatio2() ?></span>
                                    <?= $event->upDown('fora_ratio_1'); ?>
                                </div>
                            </td>
                            <?php if ($event->getNewRatio()->isFora2Hint()): ?>
                                <td style="width: 11px">
                                <span data-type="ajax"
                                      data-link="<?= Yii::app()->createUrl('/event/hint', ['event_id' => $event->getId(), 'field' => 'fora_ratio_2']) ?>"
                                      class="glyphicon glyphicon-question-sign pointer"></span>
                                </td>
                            <?php endif ?>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="column-2">
                    <table class="table table-head-body" id="bet-list">
                        <colgroup>
                            <col>
                            <col width="50px">
                            <col width="50px">
                            <col width="50px">
                        </colgroup>
                        <thead>
                        <tr>
                            <th class="strong">Тотал</th>
                            <th></th>
                            <th class="center strong">Б</th>
                            <th class="center strong">М</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr data-event="<?= $event->getId() ?>">
                            <td>
                                <?= $event->getTeam1() ?> - <?= $event->getTeam2() ?>
                            </td>
                            <td class="center">
                                <?= $DataToView->getTotalVal() ?>
                            </td>
                            <td class="center">
                                <div class="ratio">
                                    <span
                                        class="pointer c-ratio"
                                        data-num="<?= $event->getNumber() ?>"
                                        data-value="<?= $event->getNewRatio()->getTotalMore() ?>"
                                        data-type="<?= $event->getFieldAlias('total_more') ?>"
                                        data-bet="true"><?= $DataToView->getTotalMore() ?></span>
                                    <?= $event->upDown('total_more'); ?>
                                </div>
                                <?php if ($event->getNewRatio()->isTotalHint()): ?>
                                    <span data-type="ajax"
                                          data-link="<?= Yii::app()->createUrl('/event/hint', ['event_id' => $event->getId(), 'field' => 'total_more']) ?>"
                                          class="glyphicon glyphicon-question-sign pointer"></span>
                                <?php endif ?>
                            </td>
                            <td class="center">
                                <div class="ratio">
                                    <span
                                        class="pointer c-ratio"
                                        data-num="<?= $event->getNumber() ?>"
                                        data-value="<?= $event->getNewRatio()->getTotalLess() ?>"
                                        data-type="<?= $event->getFieldAlias('total_less') ?>"
                                        data-bet="true"><?= $DataToView->getTotalLess() ?></span>
                                    <?= $event->upDown('total_less'); ?>
                                </div>
                                <?php if ($event->getNewRatio()->isTotalHint()): ?>
                                    <span data-type="ajax"
                                          data-link="<?= Yii::app()->createUrl('/event/hint', ['event_id' => $event->getId(), 'field' => 'total_less']) ?>"
                                          class="glyphicon glyphicon-question-sign pointer"></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row-line">
                <div class="column">
                    <table class="table table-head-body" id="bet-list">
                        <colgroup>
                            <col>
                            <col width="50px">
                            <col width="50px">
                            <col width="50px">
                        </colgroup>
                        <thead>
                        <tr>
                            <th colspan="2" class="strong">Индивидуальный тотал</th>
                            <th class="center strong">Б</th>
                            <th class="center strong">М</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr data-event="<?= $event->getId() ?>">
                            <td>
                                <?= $event->getTeam1() ?>
                            </td>
                            <td>
                                <?= $DataToView->getItotalVal1(); ?>
                            </td>
                            <td class="center">
                                <div class="ratio">
                                    <span
                                        class="pointer c-ratio"
                                        data-num="<?= $event->getNumber() ?>"
                                        data-value="<?= $event->getNewRatio()->getItotalMore1() ?>"
                                        data-type="<?= $event->getFieldAlias('itotal_more_1') ?>"
                                        data-bet="true"><?= $DataToView->getItotalMore1() ?></span>
                                    <?= $event->upDown('itotal_more_1'); ?>
                                </div>
                            </td>
                            <td class="center">
                                <div class="ratio">
                                    <span
                                        class="pointer c-ratio"
                                        data-num="<?= $event->getNumber() ?>"
                                        data-value="<?= $event->getNewRatio()->getItotalLess1() ?>"
                                        data-type="<?= $event->getFieldAlias('itotal_less_1') ?>"
                                        data-bet="true"><?= $DataToView->getItotalLess1() ?></span>
                                    <?= $event->upDown('itotal_less_1'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr data-event="<?= $event->getId() ?>">
                            <td>
                                <?= $event->getTeam2() ?>
                            </td>
                            <td>
                                <?= $DataToView->getItotalVal2(); ?>
                            </td>
                            <td class="center">
                                <div class="ratio">
                                    <span
                                        class="pointer c-ratio"
                                        data-num="<?= $event->getNumber() ?>"
                                        data-value="<?= $event->getNewRatio()->getItotalMore2() ?>"
                                        data-type="<?= $event->getFieldAlias('itotal_more_2') ?>"
                                        data-bet="true"><?= $DataToView->getItotalMore2() ?></span>
                                    <?= $event->upDown('itotal_more_2'); ?>
                                </div>
                            </td>
                            <td class="center">
                                <div class="ratio">
                                    <span
                                        class="pointer c-ratio"
                                        data-num="<?= $event->getNumber() ?>"
                                        data-value="<?= $event->getNewRatio()->getItotalLess2() ?>"
                                        data-type="<?= $event->getFieldAlias('itotal_less_2') ?>"
                                        data-bet="true"><?= $DataToView->getItotalLess2() ?></span>
                                    <?= $event->upDown('itotal_less_2'); ?>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="content-side">
        <?php $this->widget('frontend\widgets\coupon\CouponWidget') ?>
        <?php $this->widget('frontend\widgets\event\EventWidget') ?>
    </div>
</div>