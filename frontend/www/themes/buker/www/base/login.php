<?php
/**
 * Created by PhpStorm.
 */ ?>

<form style="width: 450px;margin: 0 auto;top:40%;" id="login-form" action="<?= Yii::app()->createUrl('/base/login') ?>"
      class="ajax">
    <div class="form-group">
        <div class="col-sm-8">
            <input placeholder="" class="field form-control" name="User[login]" type="text">
        </div>
    </div>
    <div>
        <button data-type="ajax-submit" data-for="login-form" class="btn btn-primary" id="yw0" name="yt0" type="button">
            Авторизоваться
        </button>
    </div>
</form>