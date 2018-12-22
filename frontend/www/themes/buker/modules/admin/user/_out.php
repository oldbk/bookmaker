<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 20.02.2015
 * Time: 1:44
 *
 * @var \frontend\modules\user\components\USerBaseController $this
 * @var CPagination $pages
 * @var UserBalance[] $models
 * @var User $user
 * @var array $post
 * @var string $link
 * @var float $OutputKR
 * @var float $OutputEKR
 */ ?>

<div id="replace-info-block">
    <table id="in" class="table list">
        <thead>
        <tr>
            <th colspan="4">
                <form id="user-finance-in" method="get" class="ajax center" action="<?= $link ?>">
                    <ul class="list-inline">
                        <li>
                            <div class="input-daterange input-group datepicker">
                                <input type="text" class="input-sm form-control field" value="<?= $post['start'] ?>"
                                       name="UserOut[start]">
                                <span class="input-group-addon">-</span>
                                <input type="text" class="input-sm form-control field" value="<?= $post['end'] ?>"
                                       name="UserOut[end]">
                            </div>
                        </li>
                        <li>
                            <a class="btn label-active btn-sm" data-loader="true" data-for="user-finance-in"
                               data-type="ajax-submit">посмотреть</a>
                        </li>
                    </ul>
                </form>
            </th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($models as $model): ?>
            <?php switch ($model->getOperationType()) {
                case UserBalance::OPERATION_TYPE_RETURN: ?>
                    <tr>
                        <td nowrap><?= $user->buildLogin(); ?></td>
                        <td nowrap><?= date('d.m.Y H:i', $model->getCreateAt()); ?></td>
                        <td nowrap><?= $model->getPrice(); ?> <?= \common\helpers\Convert::getBalanceLabel($model->getPriceType()); ?></td>
                        <td>Отказано в выводе, возврат средств на баланс</td>
                    </tr>
                    <?php break;
                case UserBalance::OPERATION_TYPE_AKCII: ?>
                    <tr>
                        <td nowrap><?= $user->buildLogin(); ?></td>
                        <td nowrap><?= date('d.m.Y H:i', $model->getCreateAt()); ?></td>
                        <td nowrap><?= $model->getPrice(); ?> <?= \common\helpers\Convert::getBalanceLabel($model->getPriceType()); ?></td>
                        <td>Акция: <?= $model->akcii->getTitle(); ?></td>
                    </tr>
                    <?php break;
                default: ?>
                    <tr>
                        <td nowrap><?= $user->buildLogin(); ?></td>
                        <td nowrap><?= date('d.m.Y H:i', $model->getCreateAt()); ?></td>
                        <td nowrap><?= $model->getPrice(); ?> <?= \common\helpers\Convert::getBalanceLabel($model->getPriceType()); ?></td>
                        <td></td>
                    </tr>
                    <?php break;
            }
            ?>
        <?php endforeach; ?>
        <tr>
            <td class="center" colspan="4">
                <?php $this->widget('\common\extensions\pagination\Pagination', [
                    'pages' => $pages,
                    'loader_block' => true
                ]); ?>
            </td>
        </tr>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="4">
                <?= $OutputKR . 'кр/' . $OutputEKR . 'екр' ?>
            </td>
        </tr>
        </tfoot>
    </table>
</div>