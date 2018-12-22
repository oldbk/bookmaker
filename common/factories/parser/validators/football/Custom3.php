<?php
namespace common\factories\parser\validators\football;
use common\factories\parser\validators\_interface\iValidator;
use common\factories\parser\validators\Base;
use \common\factories\parser\parsers\football\Custom3 as FootballParserCustom3;

/**
 * Created by PhpStorm.
 */
class Custom3 extends Base implements iValidator
{
    private $thFieldName = [
        '№',
        'Дата',
        'Событие',
        'Т',
        'Б',
        'М',
        'П1',
        'Х',
        'П2',
        '1Х',
        '12',
        'Х2',
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

        return $count == 12;
    }

    /**
     * @return FootballParserCustom3
     */
    public function getParser()
    {
        $Parser = new FootballParserCustom3();
        $Parser->setHtml($this->getHtml())
            ->setDom($this->getDom());

        return $Parser;
    }
}