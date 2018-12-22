<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 20.02.2015
 * Time: 0:39
 *
 * @var User $model
 * @var float $price
 * @var \common\components\oldbk\Bank[] $BankList
 * @var string $link
 * @var string $field
 */ ?>

<div class="modal-dialog modal-sm" id="replacement">
    <div class="modal-content">
        <form class="ajax" id="form-bank-ekr" method="post" action="<?= $link ?>">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">Заполните форму</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <select name="<?= $field; ?>[bank]" class="form-control field">
                        <option value="0">Выберите счет</option>
                        <?php foreach ($BankList as $Bank): ?>
                            <option value="<?= $Bank->getId() ?>"><?= $Bank->getId() ?> (<?= $Bank->getEkr() ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <br>
                    <input name="<?= $field; ?>[price]" type="hidden" class="field" value="<?= $price ?>">
                    <input name="<?= $field; ?>[pass]" type="password" class="form-control field" value=""
                           placeholder="Введите пароль">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn label-none btn-sm" data-dismiss="modal">Отмена</button>
                <button type="button" class="btn label-active btn-sm" data-type="ajax-submit" data-for="form-bank-ekr">
                    Выполнить
                </button>
            </div>
        </form>
    </div>
</div>