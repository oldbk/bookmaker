<?php
namespace common\factories\transfer\kr;

use common\factories\transfer\_base\kr_ekr\BaseIO;
use common\factories\transfer\_interface\iIO;
use common\factories\transfer\traits\tKR;

/**
 * Created by PhpStorm.
 */

class Io extends BaseIO implements iIO
{
    use tKR;
}