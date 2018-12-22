<?php
namespace common\factories\parser\results;
use phpQuery;
use phpQueryObject;
use common\factories\parser\results\_interfaces\iResult;

/**
 * Created by PhpStorm.
 */
abstract class Base implements iResult
{
    /** @var null|string */
    private $_html = null;
    /** @var \phpQueryObject */
    protected $dom;
    /** @var string */
    protected $sport_title;
    /** @var string */
    protected $team1;
    /** @var string */
    protected $team2;
    /** @var array */
    protected $team_list = [];

    public function __construct($html, $team1, $team2)
    {
        $this
            ->setHtml($html)
            ->setDom(phpQuery::newDocument($html))
            ->newTeams($team1, $team2);
    }

    public function newTeams($team1, $team2)
    {
        $team1 = mb_strtolower($team1);
        $team2 = mb_strtolower($team2);

        $this
            ->setTeam1($team1)
            ->setTeam2($team2)
            ->prepareTeams();

        return $this;
    }

    /**
     * @return null|string
     */
    public function getHtml()
    {
        return $this->_html;
    }

    /**
     * @param null|string $html
     * @return $this
     */
    public function setHtml($html)
    {
        $this->_html = $html;
        return $this;
    }

    /**
     * @return phpQueryObject
     */
    public function getDom()
    {
        return $this->dom;
    }

    /**
     * @param phpQueryObject $dom
     * @return $this
     */
    public function setDom($dom)
    {
        $this->dom = $dom;
        return $this;
    }

    /**
     * @return string
     */
    public function getTeam1()
    {
        return $this->team1;
    }

    /**
     * @param string $team1
     * @return $this
     */
    public function setTeam1($team1)
    {
        $this->team1 = $team1;
        return $this;
    }

    /**
     * @return string
     */
    public function getTeam2()
    {
        return $this->team2;
    }

    /**
     * @param string $team2
     * @return $this
     */
    public function setTeam2($team2)
    {
        $this->team2 = $team2;
        return $this;
    }

    /**
     * @return array
     */
    public function getTeamList()
    {
        return $this->team_list;
    }

    /**
     * @param array $team_list
     * @return $this
     */
    public function setTeamList($team_list)
    {
        $this->team_list = $team_list;
        return $this;
    }

    protected function prepareTeams()
    {
        $returned = [];
        $returned[] = trim(preg_replace('/\(.+?\)/ui', '', $this->getTeam1()), ',.');
        $returned[] = trim(preg_replace('/\(.+?\)/ui', '', $this->getTeam2()), ',.');
        foreach ([$this->getTeam1(), $this->getTeam2()] as $team) {
            if(!in_array($team, $returned)) {
                $returned[] = trim($team);
            }

            if(preg_match('/  /ui', $team)) {
                $returned[] = trim(str_replace('  ', ' ', $team));
            }
        }

        $this->setTeamList($returned);

        return $returned;
    }

    /**
     * @param $team
     * @return bool
     */
    protected function hasTeam($team)
    {
        return in_array($team, $this->getTeamList());
    }

    /**
     * @param $team
     * @return string
     */
    protected function prepareTeam($team)
    {
        return mb_strtolower(trim(strip_tags($team), ',. '));
    }

    /**
     * @return string
     */
    public function getSportTitle()
    {
        return $this->sport_title;
    }

    /**
     * @param string $sport_title
     *
     * @return $this
     */
    public function setSportTitle($sport_title)
    {
        $this->sport_title = $sport_title;
        return $this;
    }

    /**
     * @param array $content
     *
     * @return $this
     */
    public function setContent($content)
    {
        $this->_content = $content;
        return $this;
    }

    protected $_content = [];
    public function parse()
    {
        if(!empty($this->_content)) {
            return $this->_content;
        }

        $rows = $this->getDom()->find('form table tbody tr');
        $last_title = null;
        foreach ($rows as $row) {
            $row = phpQuery::pq($row);
            $th = $row->find('th');
            if($th->count() && $th->hasClass('TH')) {
                $last_title = $this->cleanString($row->find('th')->text());
                $this->_content[$last_title] = [];

                continue;
            }

            $td = $row->find('td');
            if($td->count() != 4) {
                continue;
            }

            $info = [
                'date'      => $this->cleanString($td->eq(0)->text()),
                'team_1'    => $this->cleanString($td->eq(1)->text()),
                'team_2'    => $this->cleanString($td->eq(2)->text()),
                'result'    => $this->cleanString($td->eq(3)->text()),
            ];

            $this->_content[$last_title][] = $info;
        }

        return $this->_content;
    }

    protected function cleanString($string)
    {
        $string = str_replace('&nbsp;', '', $string);
        $temp = html_entity_decode(trim($string));

        return trim($temp, "\xC2\xA0 ");
    }

    protected function getEvent()
    {
        if(!isset($this->_content[$this->sport_title])) {
            return false;
        }

        foreach ($this->_content[$this->sport_title] as $event) {
            if($this->hasTeam(mb_strtolower($event['team_1'])) && $this->hasTeam(mb_strtolower($event['team_2']))) {
                return $event;
            }
        }

        return false;
    }

}