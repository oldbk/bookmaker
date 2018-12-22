<?php
namespace common\helpers;
/**
 * Created by PhpStorm.
 */
class SportHelper
{
    const SPORT_FOOTBALL = 'football';
    const SPORT_TENNIS = 'tennis';
    const SPORT_BASKETBALL = 'basketball';
    const SPORT_HOKKEY = 'hokkey';

    const SPORT_FOOTBALL_ID = 1;
    const SPORT_TENNIS_ID = 2;
    const SPORT_BASKETBALL_ID = 3;
    const SPORT_HOKKEY_ID = 4;

    public static function getIDByTitle($id)
    {
        $arr = [
            self::SPORT_FOOTBALL => self::SPORT_FOOTBALL_ID,
            self::SPORT_TENNIS => self::SPORT_TENNIS_ID,
            self::SPORT_BASKETBALL => self::SPORT_BASKETBALL_ID,
            self::SPORT_HOKKEY => self::SPORT_HOKKEY_ID,
        ];

        return $arr[$id];
    }

    public static function getByID($id)
    {
        $arr = [
            self::SPORT_FOOTBALL_ID => self::SPORT_FOOTBALL,
            self::SPORT_TENNIS_ID => self::SPORT_TENNIS,
            self::SPORT_BASKETBALL_ID => self::SPORT_BASKETBALL,
            self::SPORT_HOKKEY_ID => self::SPORT_HOKKEY,
        ];

        return $arr[$id];
    }

    public static function getTitleByID($id)
    {
        $arr = [
            self::SPORT_FOOTBALL_ID => 'Футбол',
            self::SPORT_TENNIS_ID => 'Теннис',
            self::SPORT_BASKETBALL_ID => 'Баскетбол',
            self::SPORT_HOKKEY_ID => 'Хоккей',
        ];

        return $arr[$id];
    }

    public static function getLink($id)
    {
        $arr = [
            self::SPORT_FOOTBALL_ID => 'https://www.betolimp.com/ru/sports/champs/football-betting',
            self::SPORT_TENNIS_ID => 'https://www.parimatch.com/sport/tennis',
            self::SPORT_BASKETBALL_ID => 'https://www.parimatch.com/sport/basketbol',
            self::SPORT_HOKKEY_ID => 'https://www.parimatch.com/sport/khokkejj',
        ];

        return $arr[$id];
    }
}