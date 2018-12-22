<?php
namespace common\factories\parser\validators\football;
use common\factories\parser\validators\_interface\iValidator;
use common\factories\parser\validators\Base;
use \common\factories\parser\parsers\football\Custom2 as FootballParserCustom2;

/**
 * Created by PhpStorm.
 */
class Custom2 extends Base implements iValidator
{
    private $thFieldName = [
        '№',
        'Дата',
        'Событие',
        'П1',
        'X',
        'П2',
    ];

    public function check()
    {
		return false;

        $count = 0;
        foreach ($this->getDom()->find('form div.wrapper table[id] tr')->eq(0)->find('th') as $key => $th) {
            if(!isset($this->thFieldName[$key]))
                return false;
            $th = \phpQuery::pq($th);
            if(strtolower($th->text()) != strtolower($this->thFieldName[$key]) || $th->attr('colspan') !== null)
                return false;

            $count++;
        }

        return $count == 6;
    }

    /**
     * @return FootballParserCustom2
     */
    public function getParser()
    {
        $Parser = new FootballParserCustom2();
        $Parser->setHtml($this->getHtml())
            ->setDom($this->getDom());

        return $Parser;
    }
}