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
 * @method \TennisEvent getEvent()
 */
class DTVTennis extends BaseDataToView
{
    private $_mapping_label = [
        'fora_ratio_1' 	=> '%team_1% (%fora_val_1%)',
        'fora_ratio_2' 	=> '%team_2% (%fora_val_2%)',
        'total_more' 	=> 'больше (%total_val%)',
        'total_less' 	=> 'меньше (%total_val%)',
        'ratio_p1' 		=> 'Победа %team_1%',
        'ratio_p2' 		=> 'Победа %team_2%',
        'ratio_20' 		=> '2:0',
        'ratio_21' 		=> '2:1',
        'ratio_12' 		=> '1:2',
        'ratio_02' 		=> '0:2',
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
        'ratio_plu15_1'   => '%team_1% +1.5 сета',
        'ratio_plu15_2'   => '%team_1% +1.5 сета',
    ];

    public function getRatio20()
    {
        return $this->getEvent()->getNewRatio()->getRatio20();
    }

    public function getRatio21()
    {
        return $this->getEvent()->getNewRatio()->getRatio21();
    }

    public function getRatio12()
    {
        return $this->getEvent()->getNewRatio()->getRatio12();
    }

    public function getRatio02()
    {
        return $this->getEvent()->getNewRatio()->getRatio02();
    }

    public function getRatioPlus151()
    {
        return $this->getEvent()->getNewRatio()->getRatioPlus151();
    }

    public function getRatioPlus152()
    {
        return $this->getEvent()->getNewRatio()->getRatioPlus152();
    }
    /**
     * @return array
     */
    public function getMappingLabel()
    {
        return $this->_mapping_label;
    }
}