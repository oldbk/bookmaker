<?php

use \common\singletons\prices\Prices;

/**
 * Created by PhpStorm.
 * User: me
 * Date: 20.02.2015
 * Time: 1:44
 *
 * @var \frontend\modules\user\components\USerBaseController $this
 * @var CPagination $pages
 * @var UserBalance[] $models
 */ ?>

<tbody>
<?php foreach ($models as $model): ?>
    <?php $price_short = Prices::init($model->getPriceType())->getShortName(); ?>
    <?php switch ($model->getPaymentType()) {
        case UserBalance::BALANCE_TYPE_PAYMENT: ?>
            <tr>
                <td>
                    <?= date('d.m.Y', $model->getCreateAt()); ?><br>
                    <?= date('H:i', $model->getCreateAt()); ?>
                </td>
                <td nowrap>ставка: <a href="javascript:void(0);" data-type="ajax"
                                      data-link="<?= Yii::app()->createUrl('/user/bet/history', ['Filter[bet-num]' => $model->getUserGroupNumber()]) ?>"
                                      data-history="true">№<?= $model->getUserGroupNumber() ?></a>,
                    выигрыш <?= $model->getPrice() . ' ' . $price_short; ?></td>
                <?php if ($model->getBalanceBefore() > 0 || $model->getBalanceAfter() > 0): ?>
                    <td>На счету: было <?= $model->getBalanceBefore() . ' ' . $price_short ?>,
                        стало <?= $model->getBalanceAfter() . ' ' . $price_short ?>.
                    </td>
                <?php else: ?>
                    <td></td>
                <?php endif ?>
            </tr>
            <?php break;
        case UserBalance::BALANCE_TYPE_INPUT: ?>
            <tr>
                <td>
                    <?= date('d.m.Y', $model->getCreateAt()); ?><br>
                    <?= date('H:i', $model->getCreateAt()); ?>
                </td>
                <td nowrap>Ввод средств: <?= $model->getPrice() . ' ' . $price_short; ?></td>
                <td>
                    <?php if ($model->getBalanceBefore() > 0 || $model->getBalanceAfter() > 0): ?>
                        На счету: было <?= $model->getBalanceBefore() . ' ' . $price_short ?>, стало <?= $model->getBalanceAfter() . ' ' . $price_short ?>.
                    <?php endif; ?>
                </td>
            </tr>
            <?php break;
    }
    ?>
<?php endforeach; ?>
<tr>
    <td class="center" colspan="3">
        <?php $this->widget('\common\extensions\pagination\Pagination', [
            'pages' => $pages,
            'loader_block' => true
        ]); ?>
    </td>
</tr>
</tbody>