<?php
/**
 * Created by PhpStorm.
 */

namespace common\factories\transfer\gold;

use common\factories\transfer\_base\kr_ekr\BasePayment;
use common\factories\transfer\_interface\iBetPayment;
use common\factories\transfer\traits\tGold;

class Payment extends BasePayment implements iBetPayment
{
    use tGold;
}