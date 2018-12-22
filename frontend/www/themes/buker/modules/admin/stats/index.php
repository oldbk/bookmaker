<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 20.02.2015
 * Time: 18:50
 *
 * @var \frontend\modules\admin\components\AdminBaseController $this
 * @var array $top
 * @var string $error_event
 * @var string $user_betting_error
 * @var string $group_error
 * @var string $strange_out
 * @var string $new_event
 * @var string $user_betting_new
 * @var string $group_new
 * @var string $finish_event
 * @var string $user_betting_finish
 * @var string $group_process
 * @var string $process_event
 * @var string $user_betting_result
 * @var string $group_ordinar
 * @var string $group_express
 * @var string $type
 */ ?>

<div class="" id="content-replacement">
    <div id="admin-stats-part">
        <table class="table list">
            <thead>
            <tr>
                <th colspan="2">События</th>
                <th colspan="2">Ставки</th>
                <th colspan="2">Группы</th>
                <th colspan="2">Баланс</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>Ошибки в событиях</td>
                <td><?= $error_event ?></td>
                <td>Ошибки в ставках</td>
                <td><?= $user_betting_error ?></td>
                <td>Ошибки в группах</td>
                <td><?= $group_error ?></td>
                <td>Странные выводы</td>
                <td><?= $strange_out ?></td>
            </tr>
            <tr>
                <td>Новые события</td>
                <td><?= $new_event ?></td>
                <td>Новые ставки</td>
                <td><?= $user_betting_new ?></td>
                <td>Новые группы</td>
                <td><?= $group_new ?></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Обработанные события</td>
                <td><?= $finish_event ?></td>
                <td>Обработанные ставки</td>
                <td><?= $user_betting_finish ?></td>
                <td>Группы в обработке</td>
                <td><?= $group_process ?></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>События в процессе</td>
                <td><?= $process_event ?></td>
                <td>Новые ставки с результатом</td>
                <td><?= $user_betting_result ?></td>
                <td>Группы ординар</td>
                <td><?= $group_ordinar ?></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>Группы экспресс</td>
                <td><?= $group_express ?></td>
                <td></td>
                <td></td>
            </tr>
            </tbody>
        </table>
        <div class="columns-2">
            <div class="box column column-1 loader-block" style="padding: 10px">
                <div class="page-dark-element">
                    <div class="page-loader-element"></div>
                </div>
                <form id="stats-finance" method="post" class="ajax"
                      action="<?= Yii::app()->createUrl('/admin/stats/special') ?>">
                    <ul class="list-inline">
                        <li>
                            <div class="input-daterange input-group datepicker">
                                <input type="text" class="input-sm form-control field" name="StatFormSpecial[start]">
                                <span class="input-group-addon">-</span>
                                <input type="text" class="input-sm form-control field" name="StatFormSpecial[end]">
                            </div>
                        </li>
                        <li>
                            <a class="btn btn-primary btn-sm" data-loader="true" data-for="stats-finance"
                               data-type="ajax-submit">посмотреть</a>
                        </li>
                    </ul>
                </form>
                <ul style="list-style: none;padding: 0;margin: 0;">
                    <li>
                        <label class="title">Значение(кр):</label> <span id="special-stats-kr">0</span>
                    </li>
                    <li>
                        <label class="title">Значение(екр):</label> <span id="special-stats-ekr">0</span>
                    </li>
                    <li>
                        <label class="title">Значение(ваучеры):</label> <span id="special-stats-voucher">0</span>
                    </li>
                </ul>
            </div>
            <div class="box column column-2">
                <div id="charts" style="width:677px;"></div>
            </div>
        </div>
        <div>
            <div class="column column-2 loader-block">
                <div class="page-dark-element">
                    <div class="page-loader-element"></div>
                </div>
                <?php $this->renderPartial('_top', ['top' => $top, 'type' => $type]); ?>
            </div>
        </div>
    </div>
</div>