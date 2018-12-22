<?php
/**
 * Created by PhpStorm.
 */

namespace common\factories\data_to_view;

use common\factories\data_to_view\_base\BaseDataToView;

/**
 * Class Football
 * @package common\factories\data_to_view
 *
 * @method \FootballEvent getEvent()
 */
class DTVHokkey extends BaseDataToView
{
    private $_mapping_label = [
        'fora_ratio_1' 	=> '%team_1% (%fora_val_1%)',
        'fora_ratio_2' 	=> '%team_2% (%fora_val_2%)',
        'total_more' 	=> 'больше (%total_val%)',
        'total_less' 	=> 'меньше (%total_val%)',
        'ratio_p1' 		=> 'Победа %team_1%',
        'ratio_x' 		=> 'Ничья',
        'ratio_p2' 		=> 'Победа %team_2%',
        'ratio_1x' 		=> '1X',
        'ratio_12' 		=> '12',
        'ratio_x2' 		=> 'X2',
        'itotal_more_1' => '%team_1% больше (%itotal_val_1%)',
        'itotal_more_2' => '%team_2% больше (%itotal_val_2%)',
        'itotal_less_1' => '%team_1% меньше (%itotal_val_1%)',
        'itotal_less_2' => '%team_2% меньше (%itotal_val_2%)',
        'fora_val_1' 	=> 'Фора команда 1',
        'fora_val_2' 	=> 'Фора команда 2',
        'total_val' 	=> 'Тотал',
        'itotal_val_1' 	=> 'Инд. тотал команда 1',
        'itotal_val_2'  => 'Инд. тотал команда 2',
        'date'          => 'Дата',
    ];

    /**
     * @return array
     */
    public function getMappingLabel()
    {
        return $this->_mapping_label;
    }
}