<?php
namespace common\factories\transfer\ekr;

use common\factories\transfer\_base\kr_ekr\BaseIO;
use common\factories\transfer\_interface\iIO;
use common\factories\transfer\traits\tEKR;

/**
 * Created by PhpStorm.
 */

class Io extends BaseIO implements iIO
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