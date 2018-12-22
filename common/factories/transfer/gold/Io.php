<?php
namespace common\factories\transfer\gold;

use common\factories\transfer\_base\kr_ekr\BaseIO;
use common\factories\transfer\_interface\iIO;
use common\factories\transfer\traits\tGold;

/**
 * Created by PhpStorm.
 */

class Io extends BaseIO implements iIO
{
    use tGold;
}