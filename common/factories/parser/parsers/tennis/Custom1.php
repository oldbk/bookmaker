<?php
/**
 * Created by PhpStorm.
 */

namespace common\factories\parser\parsers\tennis;


use common\factories\parser\parsers\_interface\iParser;
use common\factories\parser\parsers\Base;

class Custom1 extends Base implements iParser
{
    protected $tdMapping = [
        0 => 'number',
        1 => 'date',
        2 => 'teams',
        3 => 'fora_val',
        4 => 'fora_ratio',
        5 => 'total_val',
        6 => 'total_more',
        7 => 'total_less',
        8 => 'ratio_p1',
        9 => 'ratio_p2',
        10 => 'ratio_20',
        11 => 'ratio_21',
        12 => 'ratio_12',
        13 => 'ratio_02',
        14 => 'ratio_plus15',
    ];

    protected function getRatioField()
    {
        return [
            'fora_val_1',
            'fora_val_2',
            'fora_ratio_1',
            'fora_ratio_2',
            'total_val',
            'total_more',
            'total_less',
            'ratio_p1',
            'ratio_p2',
            'ratio_20',
            'ratio_21',
            'ratio_12',
            'ratio_02',
            'ratio_plus15_1',
            'ratio_plus15_2',
        ];
    }

    /**
     * @return array
     */
    protected function getPlaceholder()
    {
        return [
            'date_string'   => null,
            'date_int'      => null,
            'number'        => null,
            'team_1'        => null,
            'team_2'        => null,
            'fora_val_1'    => null,
            'fora_val_2'    => null,
            'fora_ratio_1'  => null,
            'fora_ratio_2'  => null,
            'total_val'     => null,
            'total_more'    => null,
            'total_less'    => null,
            'ratio_p1'      => null,
            'ratio_p2'      => null,
            'ratio_20'      => null,
            'ratio_21'      => null,
            'ratio_12'      => null,
            'ratio_02'      => null,
            'ratio_plus15_1'=> null,
            'ratio_plus15_2'=> null,
        ];
    }

    public function getTdMapping()
    {
        return $this->tdMapping;
    }
}