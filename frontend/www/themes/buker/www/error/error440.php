<?php
/**
 * What visitor will see in case of any error.
 *
 * @var FrontendSiteController $this
 * @var string $message
 * @var string $code
 */
$this->pageTitle .= ' - Error';
?>

<div class="" id="content-replacement">
    <div id="error-block" class="code-<?= $code; ?>">
        <table>
            <tr>
                <td rowspan="2">
                    <?= CHtml::image(Yii::app()->getStatic()->setWww()->imageLink('images/buk_error.gif', true)); ?>
                </td>
                <td class="error-code">
                    Error <?= $code ?>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="error-body">
                    Уважаемые игроки!<br>
                    В данный момент ведутся работы по обновлению проекта.<br>
                    Ориентировочное время окончания работ 09:00 по серверу ОлдБК.
                </td>
            </tr>
        </table>
    </div>
</div>