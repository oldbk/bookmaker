<?php
namespace common\traits;

/**
 * Class tEvent
 * @package common\traits
 *
 */
trait tEvent
{
    protected static $_mapping_label_drop = [
        'fora_ratio_1' 	=> 'Фора команда 1',
        'fora_ratio_2' 	=> 'Фора команда 2',
        'total_more' 	=> 'Тотал больше',
        'total_less' 	=> 'Тотал меньше',
        'ratio_p1' 		=> 'Победа команда 1',
        'ratio_x' 		=> 'Ничья',
        'ratio_p2' 		=> 'Победа команда 2',
        'ratio_1x' 		=> '1X',
        'ratio_12' 		=> '12',
        'ratio_x2' 		=> 'X2',
        'itotal_more_1' => 'Инд. тотал больше команда 1',
        'itotal_more_2' => 'Инд. тотал больше команда 2',
        'itotal_less_1' => 'Инд. тотал меньше команда 1',
        'itotal_less_2' => 'Инд. тотал меньше команда 2',
    ];

    public static function getRatioLabelDrop()
    {
        return static::$_mapping_label_drop;
    }
}