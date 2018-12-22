<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 20.02.2015
 * Time: 18:50
 *
 * @var \frontend\modules\admin\components\AdminBaseController $this
 * @var array $top
 * @var float $in
 * @var float $out
 * @var array $in_kr
 */ ?>

<div class="" id="content-replacement">
    <div id="admin-stats-part">
        <table class="table list">
            <thead>
            <tr>
                <th colspan="">Логин</th>
                <th colspan="">Ввод (<?= $in ?>)</th>
                <th colspan="">Вывод (<?= $out ?>)</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($in_kr as $login => $_item): ?>
                <?php $out = isset($out_kr[$login]) ? $out_kr[$login] : '0.00' ?>
                <tr>
                    <td><?= $login ?></td>
                    <td><?= $_item ?></td>
                    <td><?= $out; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>