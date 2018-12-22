<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 20.02.2015
 * Time: 1:44
 *
 * @var \frontend\modules\user\components\UserBaseController $this
 * @var CPagination $pages
 * @var UserBalance[] $models
 */ ?>

<div class="" id="content-replacement">
    <div class="filter">
        <form data-history="true" method="get" class="center-block ajax"
              action="<?= Yii::app()->createUrl('/admin/output/out') ?>" id="all-betting-filter">
            <ul class="list-inline">
                <li>
                    <input id="user-auto" placeholder="Начните вводить логин"
                           data-source="<?= Yii::app()->createUrl('/user/user/auto') ?>" class="form-control input-sm"
                           type="text">
                    <input type="hidden" id="user-auto-id" name="Filter[user]" class="field">
                </li>
                <li>
                    <br>
                    <a class="btn label-active btn-sm" data-history="true" id="filtered" data-for="all-betting-filter"
                       data-type="ajax-submit">Показать</a>
                    <a class="btn label-none btn-sm"
                       href="<?= Yii::app()->createUrl('/admin/output/out') ?>">Сбросить</a>
                </li>
            </ul>
        </form>
        <div class="control-btns" style="margin-bottom: 5px">
            <a
                data-type="ajax"
                data-link="<?= Yii::app()->createUrl('/admin/output/out', ['Filter[price_type]' => User::TYPE_KR]); ?>"
                href="javascript:void(0);"
                data-history="true"
                class="label label-<?= $filter['price_type'] == User::TYPE_KR ? 'active' : 'none'; ?>">кр</a>
            <a
                data-type="ajax"
                data-link="<?= Yii::app()->createUrl('/admin/output/out', ['Filter[price_type]' => User::TYPE_EKR]); ?>"
                href="javascript:void(0);"
                data-history="true"
                class="label label-<?= $filter['price_type'] == User::TYPE_EKR ? 'active' : 'none'; ?>">екр</a>
        </div>
    </div>
    <table id="in" class="table list">
        <colgroup>
            <col width="5%">
            <col>
            <col>
            <col width="100px">
        </colgroup>
        <tbody>
        <?php foreach ($models as $model): ?>
            <tr>
                <td nowrap>[<?= date('d.m.Y H:i', $model->getCreateAt()); ?>]</td>
                <td nowrap><?= $model->user->buildLogin(); ?></td>
                <td nowrap><?= $model->getPrice(); ?> <?= \common\helpers\Convert::getBalanceLabel($model->getPriceType()); ?></td>
                <td nowrap class="right">
                    <?php if (!$model->isModer()): ?>
                        <strong>Авто</strong>
                    <?php endif; ?>
                    [<?= date('d.m.Y H:i', $model->getUpdateAt()) ?>]
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
        <tr>
            <td class="center" colspan="4">
                <?php $this->widget('\common\extensions\pagination\Pagination', [
                    'pages' => $pages,
                ]); ?>
            </td>
        </tr>
        </tfoot>
    </table>
</div>