<?php
use common\singletons\prices\Prices;

/**
 * Created by PhpStorm.
 * User: me
 * Date: 20.02.2015
 * Time: 1:44
 *
 * @var UserBalance[] $models
 */ ?>

<tbody>
<?php foreach ($models as $model): ?>
    <?php $price_short = Prices::init($model->getPriceType())->getShortName(); ?>
    <?php switch ($model->getPaymentType()) {
        case UserBalance::BALANCE_TYPE_OUTPUT: ?>
            <tr>
                <td>
                    <?= date('d.m.Y', $model->getCreateAt()); ?><br>
                    <?= date('H:i', $model->getCreateAt()); ?>
                </td>
                <td nowrap>
                    Вывод средств: <?= $model->getPrice() . ' ' . $price_short ?>
                </td>
                <td colspan="2" nowrap>
                    <?php if ($model->getBalanceBefore() > 0 || $model->getBalanceAfter() > 0): ?>
                        На счету: было <?= $model->getBalanceBefore() . ' ' . $price_short ?>, стало <?= $model->getBalanceAfter() . ' ' . $price_short ?>.
                    <?php endif; ?>
                    <?php if ($model->isModer()): ?>
                        <hr style="margin: 0">
                        <?php if ($model->getStatus() == \common\interfaces\iStatus::STATUS_NEW): ?>
                            На модерации <span
                                data-confirm="true"
                                data-confirm-text="Вы уверены, что хотите отменить запрос на вывод?"
                                data-type="ajax"
                                data-link="<?= Yii::app()->createUrl('/user/finance/cancel' . $model->getPriceType(), ['balance_id' => $model->getId()]) ?>"
                                class="glyphicon glyphicon-remove pointer red refund"
                                data-toggle="tooltip"
                                title="Отозвать запрос"></span>
                        <?php elseif ($model->getStatus() == \common\interfaces\iStatus::STATUS_DISABLE): ?>
                            Отказано в выводе [<?= date('d.m.Y H:i', $model->getUpdateAt()) ?>]
                        <?php elseif ($model->getStatus() == \common\interfaces\iStatus::STATUS_CANCEL): ?>
                            Запрос отозван [<?= date('d.m.Y H:i', $model->getUpdateAt()) ?>]
                        <?php else: ?>
                            Одобрен [<?= date('d.m.Y H:i', $model->getUpdateAt()) ?>]
                        <?php endif; ?>
                    <?php endif; ?>
                </td>
            </tr>
            <?php break;
        case UserBalance::BALANCE_TYPE_BET: ?>
            <tr>
                <td>
                    <?= date('d.m.Y', $model->getCreateAt()); ?><br>
                    <?= date('H:i', $model->getCreateAt()); ?>
                </td>
                <td nowrap>
                    ставка: <a href="javascript:void(0);" data-type="ajax"
                               data-link="<?= Yii::app()->createUrl('/user/bet/history', ['Filter[bet-num]' => $model->getUserGroupNumber()]) ?>"
                               data-history="true">№<?= $model->getUserGroupNumber() ?></a>,
                    сумма: <?= $model->getPrice() . ' ' . $price_short ?>
                </td>
                <td colspan="2" nowrap>
                    <?php if ($model->getBalanceBefore() > 0 || $model->getBalanceAfter() > 0): ?>
                        На счету: было <?= $model->getBalanceBefore() . ' ' . $price_short ?>, стало <?= $model->getBalanceAfter() . ' ' . $price_short ?>.
                    <?php endif; ?>
                </td>
            </tr>
            <?php break;
    } ?>
<?php endforeach; ?>
</tbody>