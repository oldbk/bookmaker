<?php
namespace common\sport\ratio;
use common\factories\problem_event\_default\_interface\iProblemFora;
use common\factories\problem_event\_default\_interface\iProblemForaWin;
use common\factories\ratio\_default\_base\BaseFora;
use common\factories\ratio\_default\_base\BaseTotal;
use common\factories\RatioFactory;
use common\helpers\SportHelper;
use common\sport\ratio\_interfaces\iRatio;
use common\sport\ratio\_interfaces\iRatioHomepage;

/**
 * Created by PhpStorm.
 */
class Hokkey extends Base implements iRatio, iRatioHomepage, iProblemForaWin, iProblemFora
{
    /** @var float */
    protected $ratio_1x;
    /** @var float */
    protected $ratio_12;
    /** @var float */
    protected $ratio_x2;

    /**
     * @return float
     */
    public function getRatio1x()
    {
        return $this->ratio_1x;
    }

    /**
     * @param float $ratio_1x
     * @return $this
     */
    public function setRatio1x($ratio_1x)
    {
        $this->ratio_1x = $ratio_1x;
        return $this;
    }

    /**
     * @return float
     */
    public function getRatio12()
    {
        return $this->ratio_12;
    }

    /**
     * @param float $ratio_12
     * @return $this
     */
    public function setRatio12($ratio_12)
    {
        $this->ratio_12 = $ratio_12;
        return $this;
    }

    /**
     * @return float
     */
    public function getRatioX2()
    {
        return $this->ratio_x2;
    }

    /**
     * @param float $ratio_x2
     * @return $this
     */
    public function setRatioX2($ratio_x2)
    {
        $this->ratio_x2 = $ratio_x2;
        return $this;
    }

    protected $auto_require_fields = [
        'fora_val_1'    => 0,
        'fora_val_2'    => 0,
        'fora_ratio_1'  => 1.01,
        'fora_ratio_2'  => 1.01,
        'total_val'     => 0,
        'total_more'    => 1.01,
        'total_less'    => 1.01,
        'ratio_p1'      => 1.01,
        'ratio_p2'      => 1.01,
        //'ratio_1x'      => 1.01,
        'ratio_12'      => 1.01,
        //'ratio_x2'      => 1.01,
    ];

    /**
     * @return array
     */
    public function getAutoRequireFields()
    {
        return $this->auto_require_fields;
    }

    public function getAttributes()
    {
        return [
            'fora_val_1'    => $this->getForaVal1(),
            'fora_val_2'    => $this->getForaVal2(),
            'fora_ratio_1'  => $this->getForaRatio1(),
            'fora_ratio_2'  => $this->getForaRatio2(),
            'total_val'     => $this->getTotalVal(),
            'total_more'    => $this->getTotalMore(),
            'total_less'    => $this->getTotalLess(),
            'ratio_p1'      => $this->getRatioP1(),
            'ratio_x'       => $this->getRatioX(),
            'ratio_p2'      => $this->getRatioP2(),
            'ratio_1x'      => $this->getRatio1x(),
            'ratio_12'      => $this->getRatio12(),
            'ratio_x2'      => $this->getRatioX2(),
            'itotal_val_1'  => $this->getItotalVal1(),
            'itotal_val_2'  => $this->getItotalVal2(),
            'itotal_more_1' => $this->getItotalMore1(),
            'itotal_more_2' => $this->getItotalMore2(),
            'itotal_less_1' => $this->getItotalLess1(),
            'itotal_less_2' => $this->getItotalLess2(),
        ];
    }

    protected function getRatioList()
    {
        return [
            'fora_ratio_1',
            'fora_ratio_2',
            'total_more',
            'total_less',
            'ratio_p1',
            'ratio_x',
            'ratio_p2',
            'ratio_1x',
            'ratio_12',
            'ratio_x2',
            'itotal_more_1',
            'itotal_more_2',
            'itotal_less_1',
            'itotal_less_2',
        ];
    }

    public function isTotalHint()
    {
        if($this->getTotalVal() == null)
            return false;

        /** @var BaseTotal $RatioFactory */
        $RatioFactory = RatioFactory::factory(SportHelper::SPORT_HOKKEY_ID, 'total_more');
        return $RatioFactory
            ->setTotalVal($this->getTotalVal())
            ->isGandikap();
    }

    public function isFora1Hint()
    {
        if($this->getForaVal1() == null)
            return false;

        /** @var BaseFora $RatioFactory */
        $RatioFactory = RatioFactory::factory(SportHelper::SPORT_HOKKEY_ID, 'fora_ratio_1');
        return $RatioFactory->setFora($this->getForaVal1())
            ->isGandikap();
    }

    public function isFora2Hint()
    {
        if($this->getForaVal2() == null)
            return false;

        /** @var BaseFora $RatioFactory */
        $RatioFactory = RatioFactory::factory(SportHelper::SPORT_HOKKEY_ID, 'fora_ratio_2');
        return $RatioFactory->setFora($this->getForaVal2())
            ->isGandikap();
    }
}