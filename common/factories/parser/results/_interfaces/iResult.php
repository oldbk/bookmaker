<?php
/**
 * Created by PhpStorm.
 */

namespace common\factories\parser\results\_interfaces;


interface iResult
{
    /**
     * @return \common\sport\result\iResult
     */
    public function getData();

    /**
     * @param $team1
     * @param $team2
     */
    public function newTeams($team1, $team2);

    /**
     * @return array
     */
    public function parse();

    /**
     * @param $sport_title
     * @return self
     */
    public function setSportTitle($sport_title);

    /**
     * @param $content
     * @return self
     */
    public function setContent($content);
}