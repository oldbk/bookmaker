<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 10.09.2014
 * Time: 14:15
 */

namespace common\extensions\behaviors\menu\menu;

class HeadMenu
{
    /**
     * @return array
     */
    public function run()
    {
        $menu = [
            [
                'label' => 'Вся линия',
                'url' => ['/line/all'],
                'linkOptions' => [
                    'data-type' => 'ajax',
                    'data-history' => true,
                ]
            ],
            [
                'label' => 'Футбол',
                'url' => ['/sport/football/index'],
                'linkOptions' => [
                    'data-type' => 'ajax',
                    'data-history' => true,
                ]
            ],
            [
                'label' => 'Теннис',
                'url' => ['/sport/tennis/index'],
                'linkOptions' => [
                    'data-type' => 'ajax',
                    'data-history' => true,
                ]
            ],
            [
                'label' => 'Баскетбол',
                'url' => ['/sport/basketball/index'],
                'linkOptions' => [
                    'data-type' => 'ajax',
                    'data-history' => true,
                ]
            ],
            [
                'label' => 'Хоккей',
                'url' => ['/sport/hokkey/index'],
                'linkOptions' => [
                    'data-type' => 'ajax',
                    'data-history' => true,
                ]
            ],
        ];

        return $menu;
    }
} 