<?php
use \common\interfaces\iPrice;

/**
 * Created by PhpStorm.
 * User: me
 * Date: 10.02.2015
 * Time: 2:33
 *
 * @var \frontend\components\FrontendController $this
 * @var Settings $model
 * @var PriceSettings[] $prices
 * @var TbActiveForm $form
 */ ?>
<div class="" id="content-replacement">
    <?php $this->renderPartial('settings_form', ['model' => $model]) ?>
    <table>
        <colgroup>
            <col width="33%">
            <col width="33%">
            <col width="33%">
        </colgroup>
        <tr>
            <td>
                <?php $this->renderPartial('price_form', ['price' => $prices[iPrice::TYPE_KR]]) ?>
            </td>
            <td>
                <?php $this->renderPartial('price_form', ['price' => $prices[iPrice::TYPE_EKR]]) ?>
            </td>
            <td>
				<?php $this->renderPartial('price_form', ['price' => $prices[iPrice::TYPE_GOLD]]) ?>
            </td>
        </tr>
    </table>
</div>