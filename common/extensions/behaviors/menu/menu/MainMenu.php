<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 10.09.2014
 * Time: 14:15
 */

namespace common\extensions\behaviors\menu\menu;

class MainMenu
{
    /**
     * @return array
     */
    public function run()
    {
        $menu = [
            [
                'label' => 'Меню',
                'itemOptions' => ['class' => 'menu-title'],
                'template' => '<span class="glyphicon glyphicon-th-list pull-left"></span>{menu}'
            ],
            [
                'label' => 'Главная',
                'url' => ['/site/index'],
                'itemOptions' => ['class' => 'menu-item'],
                'linkOptions' => [
                    'data-type' => 'ajax',
                    'data-history' => true,
                ]
            ],
            [
                'label' => 'Результаты событий',
                'url' => ['/line/result'],
                'itemOptions' => ['class' => 'menu-item'],
                'linkOptions' => [
                    'data-type' => 'ajax',
                    'data-history' => true,
                ]
            ],
            [
                'label' => 'История ставок',
                'url' => ['/user/bet/history'],
                'itemOptions' => ['class' => 'menu-item'],
                'linkOptions' => [
                    'data-type' => 'ajax',
                    'data-history' => true,
                ]
            ],
            [
                'label' => 'Ввод\Вывод',
                'url' => ['/user/finance/index'],
                'itemOptions' => ['class' => 'menu-item'],
                'linkOptions' => [
                    'data-type' => 'ajax',
                    'data-history' => true,
                ]
            ],
            [
                'label' => 'FAQ',
                'url' => ['/site/faq'],
                'itemOptions' => ['class' => 'menu-item'],
                'linkOptions' => [
                    'data-type' => 'ajax',
                    'data-history' => true,
                ]
            ],
        ];

        return $menu;
    }
} 