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
    <div id="error-block" class="" style="background-color: #e3dabc">
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
                    <?= $message; ?>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="buttons">
                    <a class="btn label-active btn-sm" href="<?= Yii::app()->createUrl('/site/index') ?>">Вернуться на
                        главную</a>
                </td>
            </tr>
        </table>
    </div>
</div>