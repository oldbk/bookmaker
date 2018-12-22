<?php
/**
 * Created by PhpStorm.
 * User: ice
 * Date: 16.09.2015
 * Time: 1:37
 *
 * @var \common\components\Controller $this
 * @var iSportEvent[] $EventList
 * @var BettingGroup $BettingGroup
 * @var PriceSettings $Price
 * @var array $betting_type
 */ ?>

<div id="event-simulator-body">
    <hr>
    <table>
        <tbody>
            <tr>
                <td><?= $BettingGroup->user->buildLogin() ?></td>
                <td>
                    <strong>
                        <?= $BettingGroup->getBetType() == BettingGroup::TYPE_ORDINAR ? 'Ординар' : 'Экспресс' ?>
                    </strong>
                </td>
                <td><?= $BettingGroup->getRatioValue() ?> x <?= $BettingGroup->getPrice().$Price->getShortName() ?></td>
                <td>
                    <strong>
                        <?= $BettingGroup->getPaymentSum().$Price->getShortName() ?>
                    </strong>
                </td>
            </tr>
        </tbody>
    </table>
    <hr>
    <form action="<?= Yii::app()->createUrl('/admin/tools/checkSimulator') ?>" id="form-event-result">
        <input type="hidden" id="bet_id" name="bet_id" value="<?= $BettingGroup->getId() ?>">
        <?php foreach ($EventList as $Event):
            $DataToView = \common\factories\DataToViewFactory::factory($Event->getSportType(), ['Event' => $Event]);
            ?>
            <div class="problem-no-result" data-event-block="<?= $Event->getId() ?>">
                <div>
                    Ставка на:
                    <strong>
                        <?= $DataToView->getResultLabel($betting_type[$Event->getId()]); ?>
                    </strong>
                </div>
                <input type="hidden" class="field" name="event_id" value="<?= $Event->getId() ?>">
                <?php $this->renderPartial('eventView.' . $Event->getEventTypeView() . '.admin.problem.no_result', ['Event' => $Event]) ?>
                <div id="log"></div>
                <hr>
            </div>
        <?php endforeach; ?>
        <div class="row center">
            <button class="btn label-active btn-xs check">Показать</button>
            <button class="btn label-none btn-xs cancel">Отмена</button>
        </div>
    </form>
</div>
