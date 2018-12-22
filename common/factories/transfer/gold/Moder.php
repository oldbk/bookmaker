<?php
/**
 * Created by PhpStorm.
 */

namespace common\factories\transfer\gold;

use common\factories\transfer\_base\kr_ekr\BaseModer;
use common\factories\transfer\_interface\iModer;
use common\factories\transfer\traits\tGold;

class Moder extends BaseModer implements iModer
{
    use tGold;
}