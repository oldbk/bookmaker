<?php
/**
 * Created by PhpStorm.
 */

namespace common\factories\transfer\kr;

use common\factories\transfer\_base\kr_ekr\BaseBalance;
use common\factories\transfer\_interface\iBalance;
use common\factories\transfer\traits\tKR;

class Balance extends BaseBalance implements iBalance
{
    use tKR;
}