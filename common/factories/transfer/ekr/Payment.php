<?php
/**
 * Created by PhpStorm.
 */

namespace common\factories\transfer\ekr;

use common\factories\transfer\_base\kr_ekr\BasePayment;
use common\factories\transfer\_interface\iBetPayment;
use common\factories\transfer\traits\tEKR;

class Payment extends BasePayment implements iBetPayment
{
    use tEKR;
}