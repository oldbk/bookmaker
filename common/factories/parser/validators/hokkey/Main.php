<?php
namespace common\factories\parser\validators\hokkey;
use common\factories\parser\validators\_interface\iValidator;
use common\factories\parser\validators\Base;
use \common\factories\parser\parsers\hokkey\Main as HokkeyParserMain;

/**
 * Created by PhpStorm.
 */
class Main extends Base implements iValidator
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
        'X',
        'П2',
        '1X',
        '12',
        'X2',
        'iТ',
        'Б',
        'М'
    ];

    public function check()
    {
        $count = 0;
        foreach ($this->getDom()->find('form div.wrapper table[id] tr')->eq(0)->find('th') as $key => $th) {
            if(!isset($this->thFieldName[$key]))
                return false;

            $th = \phpQuery::pq($th);
            if($th->text() != $this->thFieldName[$key] || $th->attr('colspan') !== null)
                return false;

            $count++;
        }

        return $count == 17;
    }

    /**
     * @return HokkeyParserMain
     */
    public function getParser()
    {
        $Parser = new HokkeyParserMain();
        $Parser->setHtml($this->getHtml())
            ->setDom($this->getDom());

        return $Parser;
    }
}