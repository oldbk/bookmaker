<?php
/**
 * Created by PhpStorm.
 */

namespace common\factories\transfer\kr;

use common\factories\transfer\_base\kr_ekr\BasePayment;
use common\factories\transfer\_interface\iBetPayment;
use common\factories\transfer\traits\tKR;

class Payment extends BasePayment implements  iBetPayment
{
    use tKR;
}