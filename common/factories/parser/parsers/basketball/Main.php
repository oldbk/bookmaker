<?php
/**
 * Created by PhpStorm.
 */

namespace common\factories\parser\parsers\basketball;


use common\factories\parser\parsers\_interface\iParser;
use common\factories\parser\parsers\Base;

class Main extends Base implements iParser
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
        9 => 'ratio_x',
        10 => 'ratio_p2',
        11 => 'itotal_val',
        12 => 'itotal_more',
        13 => 'itotal_less',
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
            'ratio_x',
            'ratio_p2',
            'itotal_val_1',
            'itotal_val_2',
            'itotal_more_1',
            'itotal_more_2',
            'itotal_less_1',
            'itotal_less_2',
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
            'ratio_x'       => null,
            'ratio_p2'      => null,
            'itotal_val_1'  => null,
            'itotal_val_2'  => null,
            'itotal_more_1' => null,
            'itotal_more_2' => null,
            'itotal_less_1' => null,
            'itotal_less_2' => null,
        ];
    }

    public function getTdMapping()
    {
        return $this->tdMapping;
    }
}