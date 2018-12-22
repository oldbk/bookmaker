<?php
/**
 * Created by PhpStorm.
 */

namespace common\factories\transfer\kr;

use common\factories\transfer\_base\kr_ekr\BaseModer;
use common\factories\transfer\_interface\iModer;
use common\factories\transfer\traits\tKR;;

class Moder extends BaseModer implements iModer
{
    use tKR;
}