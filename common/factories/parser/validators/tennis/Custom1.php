<?php
namespace common\factories\parser\validators\tennis;
use common\factories\parser\validators\_interface\iValidator;
use common\factories\parser\validators\Base;
use \common\factories\parser\parsers\tennis\Main as TennisParserMain;

/**
 * Created by PhpStorm.
 */
class Custom1 extends Base implements iValidator
{
    private $thFieldName = [
        '№',
        'Дата',
        'Событие',
        'Фора',
        'КФ',
        'Т',
        'Б',
        'М',
        'П1',
        'П2',
        '2:0',
        '2:1',
        '1:2',
        '0:2',
        '+1.5 сета',
    ];

    public function check()
    {
        $count = 0;
        foreach ($this->getDom()->find('form div.wrapper table[id] tr')->eq(0)->find('th') as $key => $th) {
            if(!isset($this->thFieldName[$key]))
                return false;

            $th = \phpQuery::pq($th);
            if(strtolower($th->text()) != strtolower($this->thFieldName[$key]))
                return false;

            $count++;
        }

        return $count == 15;
    }

    /**
     * @return TennisParserMain
     */
    public function getParser()
    {
        $Parser = new TennisParserMain();
        $Parser->setHtml($this->getHtml())
            ->setDom($this->getDom());

        return $Parser;
    }
}