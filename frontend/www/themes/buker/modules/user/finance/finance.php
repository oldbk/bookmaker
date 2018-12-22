<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 10.02.2015
 * Time: 0:12
 *
 * @var \frontend\components\FrontendController $this
 * @var User $model
 * @var UserOutputRequest[] $RequestList
 * @var float $outPutKr
 * @var float $outPutEkr
 * @var float $outPutGold
 * @var float $ekrInProcess
 * @var float $krInProcess
 * @var float $goldInProcess
 *
 */ ?>
<div id="content-replacement">
    <div id="finance-part">
        <div class="columns-2" style="margin-bottom: 10px;">
            <div class="column box column-1" style="width:1023px;min-height: 135px;">
                <ul class="money-in">
                    <li class="title">Информация</li>
                    <li class="item">
                        <label>Игровой счет:</label> <?= $model->getId(); ?>
                    </li>
                    <li class="item">
                        <label>Владелец счета:</label>

                        <div style="display: inline-block;"><?= $model->buildLogin(); ?></div>
                    </li>
                    <li class="item">
                        <label>Сумма неразыгранных:</label> <?= $krInProcess ?>кр / <?= $ekrInProcess ?>екр / <?= $goldInProcess ?>мон
                    </li>
                    <li class="item">
                        <label>Заказано на выплату:</label> <?= $outPutKr ?>кр / <?= $outPutEkr ?>екр / <?= $outPutGold ?>мон
                    </li>
                </ul>
            </div>
        </div>
        <div class="columns-2">
            <div class="column box column-1">
                <ul class="money-in">
                    <li class="title">Пополнить счет</li>
                    <li class="item">
                        <div class="sub-title">1. Еврокредиты</div>
                        <div class="hint">
                            Для пополнения счета в еврокредитах введите требуемое кол-во и нажмите "Оплатить".
                        </div>
                        <form id="finance-in-ekr" class="ajax"
                              action="<?= Yii::app()->createUrl('/user/finance/in_ekr') ?>">
                            <ul class="list-inline">
                                <li>
                                    <div class="input-group">
                                        <input name="ekrIn[price]" id="ekrIn_price" type="text"
                                               class="form-control input-sm field double">
                                        <span class="input-group-addon">екр</span>
                                    </div>
                                </li>
                                <li>
                                    <a data-confirm="true" data-added-value-id="ekrIn_price" data-added="value"
                                       data-confirm-text="Вы уверены, что хотите пополнить счет на %val% екр?"
                                       class="btn label-active btn-sm" data-for="finance-in-ekr"
                                       data-type="ajax-submit">оплатить</a>
                                </li>
                            </ul>
                        </form>
                    </li>
                    <!--<li class="item">
                        <div class="sub-title">2. Кредиты</div>
                        <div class="hint">
                            Для пополнения счета в кредитах введите требуемое кол-во и нажмите "Оплатить".
                        </div>
                        <form id="finance-in-kr" class="ajax"
                              action="<?= Yii::app()->createUrl('/user/finance/in_kr') ?>">
                            <ul class="list-inline">
                                <li>
                                    <div class="input-group">
                                        <input name="krIn[price]" id="krIn_price" type="text"
                                               class="form-control input-sm double field">
                                        <span class="input-group-addon">кр</span>
                                    </div>
                                </li>
                                <li>
                                    <a data-confirm="true" data-added-value-id="krIn_price" data-added="value"
                                       data-confirm-text="Вы уверены, что хотите пополнить счет на %val% кр?"
                                       class="btn label-active btn-sm" data-for="finance-in-kr" data-type="ajax-submit">оплатить</a>
                                </li>
                            </ul>
                        </form>
                    </li>-->
                    <li class="item">
                        <div class="sub-title">2. Монеты</div>
                        <div class="hint">
                            Для пополнения счета в монетах введите требуемое кол-во и нажмите "Оплатить".
                        </div>
                        <form id="finance-in-gold" class="ajax"
                              action="<?= Yii::app()->createUrl('/user/finance/in_gold') ?>">
                            <ul class="list-inline">
                                <li>
                                    <div class="input-group">
                                        <input name="goldIn[price]" id="goldIn_price" type="text"
                                               class="form-control input-sm double field">
                                        <span class="input-group-addon">мон</span>
                                    </div>
                                </li>
                                <li>
                                    <a data-confirm="true" data-added-value-id="goldIn_price" data-added="value"
                                       data-confirm-text="Вы уверены, что хотите пополнить счет на %val% мон?"
                                       class="btn label-active btn-sm" data-for="finance-in-gold" data-type="ajax-submit">оплатить</a>
                                </li>
                            </ul>
                        </form>
                    </li>
                </ul>
            </div>
            <div class="column box column-2">
                <ul class="money-out">
                    <li class="title">Вывести</li>
                    <li class="item">
                        <div class="sub-title">1. Еврокредиты</div>
                        <div class="hint">Для вывода еврокредитов введите кол-во и нажмите "Вывести"</div>
                        <form id="finance-out-ekr" class="ajax"
                              action="<?= Yii::app()->createUrl('/user/finance/out_ekr') ?>">
                            <ul class="list-inline">
                                <li>
                                    <div class="input-group">
                                        <input name="ekrOut[price]" id="ekrOut_price" type="text"
                                               class="form-control input-sm double field">
                                        <span class="input-group-addon">екр</span>
                                    </div>
                                </li>
                                <li>
                                    <a data-confirm="true" data-added-value-id="ekrOut_price" data-added="value"
                                       data-confirm-text="Вы уверены, что хотите вывести %val% eкр?"
                                       class="btn label-active btn-sm" data-for="finance-out-ekr"
                                       data-type="ajax-submit">вывести</a>
                                </li>
                            </ul>
                        </form>
                    </li>
                    <!--<li class="item">
                        <div class="sub-title">2. Кредиты</div>
                        <div class="hint">Для вывода кредитов введите кол-во и нажмите "Вывести"</div>
                        <form id="finance-out-kr" class="ajax"
                              action="<?= Yii::app()->createUrl('/user/finance/out_kr') ?>">
                            <ul class="list-inline">
                                <li>
                                    <div class="input-group">
                                        <input name="krOut[price]" id="krOut_price" type="text"
                                               class="form-control input-sm double field">
                                        <span class="input-group-addon">кр</span>
                                    </div>
                                </li>
                                <li>
                                    <a data-confirm="true" data-added-value-id="krOut_price" data-added="value"
                                       data-confirm-text="Вы уверены, что хотите вывести %val% кр?"
                                       class="btn label-active btn-sm" data-for="finance-out-kr"
                                       data-type="ajax-submit">вывести</a>
                                </li>
                            </ul>
                        </form>
                    </li>-->
                    <li class="item">
                        <div class="sub-title">2. Монеты</div>
                        <div class="hint">Для вывода монет введите кол-во и нажмите "Вывести"</div>
                        <form id="finance-out-gold" class="ajax"
                              action="<?= Yii::app()->createUrl('/user/finance/out_gold') ?>">
                            <ul class="list-inline">
                                <li>
                                    <div class="input-group">
                                        <input name="goldOut[price]" id="goldOut_price" type="text"
                                               class="form-control input-sm double field">
                                        <span class="input-group-addon">мон</span>
                                    </div>
                                </li>
                                <li>
                                    <a data-confirm="true" data-added-value-id="goldOut_price" data-added="value"
                                       data-confirm-text="Вы уверены, что хотите вывести %val% мон?"
                                       class="btn label-active btn-sm" data-for="finance-out-gold"
                                       data-type="ajax-submit">вывести</a>
                                </li>
                            </ul>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
        <div class="columns-2">
            <div class="column column-1 loader-block">
                <div class="page-dark-element">
                    <div class="page-loader-element"></div>
                </div>
                <table id="in" class="table list">
                    <thead>
                    <tr>
                        <th class="center">История-ввод</th>
                        <th colspan="4">
                            <form id="finance-in" method="get" class="ajax"
                                  action="<?= Yii::app()->createUrl('/user/finance/in') ?>">
                                <ul class="list-inline">
                                    <li>
                                        <div class="input-daterange input-group datepicker">
                                            <input type="text" class="input-sm form-control input-sm field"
                                                   name="In[start]">
                                            <span class="input-group-addon">-</span>
                                            <input type="text" class="input-sm form-control input-sm field"
                                                   name="In[end]">
                                        </div>
                                    </li>
                                    <li>
                                        <a class="btn label-active btn-sm" data-loader="true" data-for="finance-in"
                                           data-type="ajax-submit">посмотреть</a>
                                    </li>
                                </ul>
                            </form>
                        </th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <div class="column column-2 loader-block">
                <div class="page-dark-element">
                    <div class="page-loader-element"></div>
                </div>
                <table id="out" class="table list">
                    <thead>
                    <tr>
                        <th class="center">История-вывод</th>
                        <th colspan="4">
                            <form id="finance-out" method="get" class="ajax"
                                  action="<?= Yii::app()->createUrl('/user/finance/out') ?>">
                                <ul class="list-inline">
                                    <li>
                                        <div class="input-daterange input-group datepicker">
                                            <input type="text" class="input-sm form-control field" name="Out[start]">
                                            <span class="input-group-addon">-</span>
                                            <input type="text" class="input-sm form-control field" name="Out[end]">
                                        </div>
                                    </li>
                                    <li>
                                        <a class="btn label-active btn-sm" data-loader="true" data-for="finance-out"
                                           data-type="ajax-submit">посмотреть</a>
                                    </li>
                                </ul>
                            </form>
                        </th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>