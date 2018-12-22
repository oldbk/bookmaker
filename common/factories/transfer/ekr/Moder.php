<?php
/**
 * Created by PhpStorm.
 */

namespace common\factories\transfer\ekr;

use common\factories\transfer\_base\kr_ekr\BaseModer;
use common\factories\transfer\_interface\iModer;
use common\factories\transfer\traits\tEKR;

class Moder extends BaseModer implements iModer
{
    use tEKR;

    /**
     * @param $user
     * @param float $bankid
     */
    public function __construct(&$user, $bankid = null)
    {
        parent::__construct($user);
        $this->setBankId($bankid);
    }
}