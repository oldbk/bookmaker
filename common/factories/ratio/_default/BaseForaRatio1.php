<?php

namespace common\factories\ratio\_default;
use common\factories\ratio\_default\_base\BaseFora;
use common\factories\ratio\_default\_interface\iRatio;
use common\interfaces\iStatus;

/**
 * Class ForaRatio1
 * @package common\factories\ratio
 */
abstract class BaseForaRatio1 extends BaseFora implements iRatio
{
    abstract protected function getForaVal1();

    /**
     * @return int
     */
    public function check()
    {
        $this->setFora($this->getForaVal1());
        if(!$this->isGandikap())
            $this->defaultPrepare();
        else
            $this->decimalPrepare();
    }

    protected function prepare($foraVal)
    {
        $msg = \CHtml::openTag('ul', ['class' => 'log']);
        $msg .= \CHtml::tag('li', [], sprintf('Результат 1: %s', $this->getEvent()->getResult()->getTeam1Result()));
        $msg .= \CHtml::tag('li', [], sprintf('Результат 2: %s', $this->getEvent()->getResult()->getTeam2Result()));
        $msg .= \CHtml::tag('li', [], sprintf('Фора: %s', $foraVal));

        $r = $this->getEvent()->getResult()->getTeam1Result() + $foraVal;
        $msg .= \CHtml::tag('li', [], sprintf('Сумма: %s', $r));
        $msg .= \CHtml::tag('li', [], sprintf('Операция: %s > %s = %s',
            $r,
            $this->getEvent()->getResult()->getTeam2Result(),
            $r > $this->getEvent()->getResult()->getTeam2Result() ? 'TRUE' : 'FALSE'
        ));

        if($r > $this->getEvent()->getResult()->getTeam2Result()) {
            $result = iStatus::RESULT_WIN;
            $msg .= \CHtml::tag('li', [], 'Итог: Сыграла');
        } elseif($r == $this->getEvent()->getResult()->getTeam2Result()) {
            $result = iStatus::RESULT_SET_K_1;
            $msg .= \CHtml::tag('li', [], 'Итог: Возврат');
        } else {
            $result = iStatus::RESULT_LOSS;
            $msg .= \CHtml::tag('li', [], 'Итог: Не сыграла');
        }

        $msg .= \CHtml::closeTag('ul');
        $this->addExplain($msg);
        return $result;
    }

    public function getHint()
    {
        $this->setFora($this->getForaVal1());
        if(!$this->isGandikap())
            return [];

        return $this->getHintTeam($this->getEvent()->getTeam1());
    }
}