<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 11.02.2015
 * Time: 20:51
 *
 * @var UserBetting[] $historyList
 * @var BettingGroup $BettingGroup
 * @var iSportEvent[] $events
 */
use \common\helpers\Convert;
use \common\interfaces\iStatus;

?>

<table border="1" class="bet-event-list table table-head-body">
    <colgroup span="1" width="1%"></colgroup>
    <colgroup span="1" width="5%"></colgroup>
    <colgroup span="1" width="40%"></colgroup>
    <colgroup span="1" width="2%"></colgroup>
    <colgroup span="1" width="2%"></colgroup>
    <thead>
    <tr>
        <th colspan="3">
            <?= $BettingGroup->user->buildLogin(); ?>
            <?= sprintf(
                'Группа №%d %s Сумма: %s %s',
                $BettingGroup->getId(),
                date('d/m/Y H:i', $BettingGroup->getCreateAt()),
                $BettingGroup->getPrice(),
                Convert::getBalanceLabel($BettingGroup->getPriceType())) ?></th>
        <th colspan="2" style="text-align: right;min-width: 140px;" nowrap>
            <?php
            switch (true) {
                case ($BettingGroup->getResultStatus() == iStatus::RESULT_WIN || $BettingGroup->getResultStatus() == iStatus::RESULT_SET_K_1):
                    echo sprintf('<div class="win">%s</div> %s', $BettingGroup->getPaymentSum(), Convert::getBalanceLabel($BettingGroup->getPriceType()));
                    break;
                case ($BettingGroup->getResultStatus() == iStatus::RESULT_LOSS):
                    echo sprintf('<div class="loss">0.00</div> %s', Convert::getBalanceLabel($BettingGroup->getPriceType()));
                    break;
                case ($BettingGroup->getResultStatus() == iStatus::RESULT_RETURN):
                    echo sprintf('<div class="return">%s</div> %s', $BettingGroup->getPaymentSum(), Convert::getBalanceLabel($BettingGroup->getPriceType()));
                    break;
                default:
                    //echo sprintf('0.00 %s', Convert::getBalanceLabel($BettingGroup->getPriceType()));
                    break;
            }
            if ($BettingGroup->getStatus() == iStatus::STATUS_HAVE_RESULT)
                echo ' <em>(расчитывается)</em>';
            if ($BettingGroup->isRefund())
                echo ' <em>Отозвал ставку</em>';
            ?>
        </th>
    </tr>
    </thead>
    <tbody>
    <?php
    $time = 0;
    foreach ($historyList as $item):
        $event = $events[$item->getEventId()];
        $DataToView = \common\factories\DataToViewFactory::factory($event->getSportType(), ['Event' => $event]);
        if ($time == 0) $time = $event->getDateInt();
        if ($time > $event->getDateInt()) $time = $event->getDateInt();
        ?>
        <tr>
            <td class="center">
                <?= CHtml::link('№' . $item->getEventId(), Yii::app()->createUrl('/admin/event/all-betting', ['Filter[event_id]' => $item->getEventId()]), ['data-type' => 'ajax', 'data-history' => 'true']) ?>
            </td>
            <td class="center">
                <?= date('d.m.Y H:i', $event->getDateInt()) ?>
            </td>
            <td>
                <?= $DataToView->getTitle(); ?>:
                <?= $DataToView->getResultLabel($item->getRatioType()); ?>
            </td>
            <td class="" style="text-align: right">
                <?php switch (true) {
                    case ($item->getResultStatus() == iStatus::RESULT_WIN || $item->getResultStatus() == iStatus::RESULT_SET_K_1):
                        echo sprintf('<div class="win">%s</div>', $item->getRatioValue());
                        break;
                    case ($item->getResultStatus() == iStatus::RESULT_LOSS):
                        echo sprintf('<div class="loss">%s</div>', $item->getRatioValue());
                        break;
                    case ($item->getResultStatus() == iStatus::RESULT_RETURN):
                        echo sprintf('<div class="return">%s</div>', $item->getRatioValue());
                        break;
                    default:
                        echo $item->getRatioValue();
                        break;
                }
                echo $item->getRatioValue() != $event->getNewRatio()->getOrigin($item->getRatioType()) ? sprintf(' (%s)', $event->getNewRatio()->getOrigin($item->getRatioType())) : '' ?>
            </td>
            <td nowrap class="" style="text-align: right">
                <?php if (!$event->getResult()->isEmpty()): ?>
                    <?= $event->getResult()->getResultString() ?>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    <tr>
        <td colspan="4" style="text-align: right;">
            <?php if ($BettingGroup->getBetType() == BettingGroup::TYPE_EXPRESS): ?>
                Суммарный коэф.: <strong><?= $BettingGroup->getRatioValue(); ?></strong>
            <?php endif; ?>
        </td>
        <td colspan="1" style="text-align: right;">
            <?php if ($BettingGroup->getStatus() != BettingGroup::STATUS_FINISH && $time > time()): ?>
                <span
                    data-type="ajax"
                    data-link="<?= Yii::app()->createUrl('/admin/event/refund', ['bet_id' => $BettingGroup->getId(), 'event_id' => $item->getEventId()]) ?>"
                    class="glyphicon glyphicon-remove pointer red refund"
                    data-toggle="tooltip"
                    title="Отозвать ставку"></span>
            <?php endif; ?>
        </td>
    </tr>
    </tbody>
</table>