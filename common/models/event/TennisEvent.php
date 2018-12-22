<?php
use common\helpers\SportHelper;
/**
 * Created by PhpStorm.
 * @method \common\sport\ratio\Tennis getNewRatio()
 * @method \common\sport\ratio\Tennis getOldRatio()
 * @method \common\sport\result\Tennis getResult()
 * @method \TennisEvent copy()
 */
class TennisEvent extends SportEvent implements iSportEvent
{
    /**
     * @param string $className
     * @return TennisEvent
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    public function init()
    {
        parent::init();

        $this
            ->setOldRatio(new \common\sport\ratio\Tennis())
            ->setNewRatio(new \common\sport\ratio\Tennis())
            ->setResult(new \common\sport\result\Tennis());
    }

    public function defaultScope()
    {
        $t = $this->getTableAlias(false, false);
        return [
            'condition' => $t.'.sport_type = :'.$t.'sport_type',
            'params' => [':'.$t.'sport_type' => SportHelper::SPORT_TENNIS_ID]
        ];
    }

    public function beforeValidate()
    {
        $r = parent::beforeValidate();
        $this->sport_type = SportHelper::SPORT_TENNIS_ID;

        return $r;
    }

    public function upDown($field)
    {
        $last_value = $this->getOldRatio()->getAttribute($field);
        $new_value = $this->getNewRatio()->getAttribute($field);
        if($last_value === null || $new_value === null || $new_value == $last_value)
            return null;
        switch (true) {
            case ($new_value > $last_value): //поднялся
                $diff = $new_value - $last_value;
                return '<span class="glyphicon glyphicon-arrow-up ratio-up green" title="+'.$diff.'" data-toggle="tooltip"></span>';
                break;
            case ($new_value < $last_value): //опустился
                $diff = $last_value - $new_value;
                return '<span class="glyphicon glyphicon-arrow-down ratio-down red" title="-'.$diff.'" data-toggle="tooltip"></span>';
                break;
            default:
                return null;
                break;
        }
    }

    /**
     * @var array
     *
     * шифруем параметры для ставок
     */
    private $_mapping = [
        'fora_ratio_1' 	    => 'fr1',
        'fora_ratio_2' 	    => 'fr2',
        'total_more' 	    => 'tm',
        'total_less' 	    => 'tl',
        'ratio_p1' 		    => 'p1',
        'ratio_p2' 		    => 'p2',
        'ratio_20' 		    => '20',
        'ratio_21' 		    => '21',
        'ratio_12' 		    => '12',
        'ratio_02' 		    => '02',
        'ratio_plus15_1'    => 'plus15_1',
        'ratio_plus15_2'    => 'plus15_2',
        'itotal_more_1' => 'itm1',
        'itotal_more_2' => 'itm2',
        'itotal_less_1' => 'itl1',
        'itotal_less_2' => 'itl2',
    ];

    /**
     * @param $field
     * @return null
     */
    public function getFieldAlias($field)
    {
        return isset($this->_mapping[$field]) ? $this->_mapping[$field] : null;
    }

    /**
     * @param $alias
     * @return null
     */
    public function getFieldByAlias($alias)
    {
        return ($field = array_search($alias, $this->_mapping)) ? $field : null;
    }

    /**
     * @return string
     * @deprecated
     */
    public function getViewFactoryTitle()
    {
        return 'tennis';
    }

    public function haveDiff()
    {
        if($this->getOldRatio()->isEmpty() === false)
            return $this->getNewRatio()->getAttributes() != $this->getOldRatio()->getAttributes();

        return false;
    }

    public function getMapping()
    {
        return $this->_mapping;
    }
}