<?php
/**
 * Created by PhpStorm.
 */

namespace common\factories\transfer\ekr;

use common\factories\transfer\_base\kr_ekr\BaseBetting;
use common\factories\transfer\_interface\iBetPayment;
use common\factories\transfer\traits\tEKR;

class Betting extends BaseBetting implements iBetPayment
{
    use tEKR;
}