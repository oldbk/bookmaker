<?php
/**
 * Created by PhpStorm.
 */

namespace common\factories\transfer\kr;

use common\factories\transfer\_base\kr_ekr\BaseBetting;
use common\factories\transfer\_interface\iBetPayment;
use common\factories\transfer\traits\tKR;

class Betting extends BaseBetting implements iBetPayment
{
    use tKR;
}