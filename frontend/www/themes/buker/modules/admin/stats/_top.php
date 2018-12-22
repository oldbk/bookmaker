<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 20.02.2015
 * Time: 20:16
 *
 * @var array $top
 * @var string $type
 */ ?>
<div id="replace">
    <div class="control-btns" style="margin-bottom: 5px">
        <a
            data-type="ajax"
            data-link="<?= Yii::app()->createUrl('/admin/stats/top', ['type' => User::TYPE_KR]); ?>"
            href="javascript:void(0);"
            class="label label-<?= $type == User::TYPE_KR ? 'active' : 'none'; ?>">кр</a>
        <a
            data-type="ajax"
            data-link="<?= Yii::app()->createUrl('/admin/stats/top', ['type' => User::TYPE_EKR]); ?>"
            href="javascript:void(0);"
            class="label label-<?= $type == User::TYPE_EKR ? 'active' : 'none'; ?>">екр</a>
    </div>
    <ul class="list-inline">
        <li style="width: 340px">
            <div class="panel panel-default top-1">
                <div class="panel-heading">Топ 20 ввод</div>
                <div class="panel-body">
                    <table class="table list">
                        <thead>
                        <tr>
                            <th>Логин</th>
                            <th>Сумма</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($top['user_in'] as $user): ?>
                            <tr>
                                <td><?= $user['login']; ?></td>
                                <td><?= $user['price']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </li>
        <li style="width: 340px">
            <div class="panel panel-default top-2">
                <div class="panel-heading">Топ 20 вывод</div>
                <div class="panel-body">
                    <table class="table list">
                        <thead>
                        <tr>
                            <th>Логин</th>
                            <th>Сумма</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($top['user_out'] as $user): ?>
                            <tr>
                                <td><?= $user['login']; ?></td>
                                <td><?= $user['price']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </li>
        <li style="width: 340px">
            <div class="panel panel-default top-3">
                <div class="panel-heading">Топ 20 баланс</div>
                <div class="panel-body">
                    <table class="table list">
                        <thead>
                        <tr>
                            <th>Логин</th>
                            <th>Сумма</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($top['user_balance'] as $user): ?>
                            <tr>
                                <td><?= $user['login']; ?></td>
                                <td><?= $user['price']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </li>
    </ul>
</div>