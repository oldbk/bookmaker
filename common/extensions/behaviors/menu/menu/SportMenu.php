<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 10.09.2014
 * Time: 14:15
 */

namespace common\extensions\behaviors\menu\menu;

use common\helpers\SportHelper;

class SportMenu
{
    /**
     * @return array
     */
    public function football()
    {
        $menu = [
            [
                'label' => 'Футбол',
                'itemOptions' => ['class' => 'menu-title'],
                'template' => '<span class="glyphicon glyphicon-th-list pull-left"></span>{menu}'
            ],
            [
                'label' => 'Время',
                'itemOptions' => ['class' => 'menu-sub-title'],
            ],
            [
                'label' => 'Сегодня',
                'url' => ['/sport/football/index', 'date' => 'today'],
                'itemOptions' => ['class' => 'menu-item'],
                'linkOptions' => [
                    'data-type' => 'ajax',
                    'data-history' => true,
                ]
            ],
            [
                'label' => 'Завтра',
                'url' => ['/sport/football/index', 'date' => 'tomorrow'],
                'itemOptions' => ['class' => 'menu-item'],
                'linkOptions' => [
                    'data-type' => 'ajax',
                    'data-history' => true,
                ]
            ],
            [
                'label' => 'Топ Соревнований',
                'itemOptions' => ['class' => 'menu-sub-title'],
            ],
        ];

        $criteria = new \CDbCriteria();
        $criteria->select = ['id', 'title'];
        $criteria->addCondition('event_count > 0');
        $criteria->order = 'event_count desc';
        $criteria->limit = 5;
        /** @var \Football[] $SportList */
        $SportList = \Football::model()->findAll($criteria);
        foreach ($SportList as $Sport)
            $menu[] = [
                'label' => trim(str_replace('Футбол', '', $Sport->getTitle()), ' .'),
                'url' => ['/sport/football/index', 'sport' => $Sport->getId()],
                'itemOptions' => ['class' => 'menu-item'],
                'linkOptions' => [
                    'data-type' => 'ajax',
                    'data-history' => true,
                ]
            ];

        return $menu;
    }

    /**
     * @return array
     */
    public function tennis()
    {
        $menu = [
            [
                'label' => 'Теннис',
                'itemOptions' => ['class' => 'menu-title'],
                'template' => '<span class="glyphicon glyphicon-th-list pull-left"></span>{menu}'
            ],
            [
                'label' => 'Время',
                'itemOptions' => ['class' => 'menu-sub-title'],
            ],
            [
                'label' => 'Сегодня',
                'url' => ['/sport/tennis/index', 'date' => 'today'],
                'itemOptions' => ['class' => 'menu-item'],
                'linkOptions' => [
                    'data-type' => 'ajax',
                    'data-history' => true,
                ]
            ],
            [
                'label' => 'Завтра',
                'url' => ['/sport/tennis/index', 'date' => 'tomorrow'],
                'itemOptions' => ['class' => 'menu-item'],
                'linkOptions' => [
                    'data-type' => 'ajax',
                    'data-history' => true,
                ]
            ],
            [
                'label' => 'Топ Соревнований',
                'itemOptions' => ['class' => 'menu-sub-title'],
            ],
        ];

        $criteria = new \CDbCriteria();
        $criteria->select = ['id', 'title'];
        $criteria->addCondition('event_count > 0');
        $criteria->order = 'event_count desc';
        $criteria->limit = 5;
        /** @var \Tennis[] $SportList */
        $SportList = \Tennis::model()->findAll($criteria);
        foreach ($SportList as $Sport)
            $menu[] = [
                'label' => trim(str_replace('Теннис', '', $Sport->getTitle()), ' .'),
                'url' => ['/sport/tennis/index', 'sport' => $Sport->getId()],
                'itemOptions' => ['class' => 'menu-item'],
                'linkOptions' => [
                    'data-type' => 'ajax',
                    'data-history' => true,
                ]
            ];

        return $menu;
    }

    /**
     * @return array
     */
    public function basketball()
    {
        $menu = [
            [
                'label' => 'Байскетбол',
                'itemOptions' => ['class' => 'menu-title'],
                'template' => '<span class="glyphicon glyphicon-th-list pull-left"></span>{menu}'
            ],
            [
                'label' => 'Время',
                'itemOptions' => ['class' => 'menu-sub-title'],
            ],
            [
                'label' => 'Сегодня',
                'url' => ['/sport/basketball/index', 'date' => 'today'],
                'itemOptions' => ['class' => 'menu-item'],
                'linkOptions' => [
                    'data-type' => 'ajax',
                    'data-history' => true,
                ]
            ],
            [
                'label' => 'Завтра',
                'url' => ['/sport/basketball/index', 'date' => 'tomorrow'],
                'itemOptions' => ['class' => 'menu-item'],
                'linkOptions' => [
                    'data-type' => 'ajax',
                    'data-history' => true,
                ]
            ],
            [
                'label' => 'Топ Соревнований',
                'itemOptions' => ['class' => 'menu-sub-title'],
            ],
        ];

        $criteria = new \CDbCriteria();
        $criteria->select = ['id', 'title'];
        $criteria->addCondition('event_count > 0');
        $criteria->order = 'event_count desc';
        $criteria->limit = 5;
        /** @var \Basketball[] $SportList */
        $SportList = \Basketball::model()->findAll($criteria);
        foreach ($SportList as $Sport)
            $menu[] = [
                'label' => trim(str_replace('Байскетбол', '', $Sport->getTitle()), ' .'),
                'url' => ['/sport/basketball/index', 'sport' => $Sport->getId()],
                'itemOptions' => ['class' => 'menu-item'],
                'linkOptions' => [
                    'data-type' => 'ajax',
                    'data-history' => true,
                ]
            ];

        return $menu;
    }

    /**
     * @return array
     */
    public function hokkey()
    {
        $menu = [
            [
                'label' => 'Хоккей',
                'itemOptions' => ['class' => 'menu-title'],
                'template' => '<span class="glyphicon glyphicon-th-list pull-left"></span>{menu}'
            ],
            [
                'label' => 'Время',
                'itemOptions' => ['class' => 'menu-sub-title'],
            ],
            [
                'label' => 'Сегодня',
                'url' => ['/sport/hokkey/index', 'date' => 'today'],
                'itemOptions' => ['class' => 'menu-item'],
                'linkOptions' => [
                    'data-type' => 'ajax',
                    'data-history' => true,
                ]
            ],
            [
                'label' => 'Завтра',
                'url' => ['/sport/hokkey/index', 'date' => 'tomorrow'],
                'itemOptions' => ['class' => 'menu-item'],
                'linkOptions' => [
                    'data-type' => 'ajax',
                    'data-history' => true,
                ]
            ],
            [
                'label' => 'Топ Соревнований',
                'itemOptions' => ['class' => 'menu-sub-title'],
            ],
        ];

        $criteria = new \CDbCriteria();
        $criteria->select = ['id', 'title'];
        $criteria->addCondition('event_count > 0');
        $criteria->order = 'event_count desc';
        $criteria->limit = 5;
        /** @var \Hokkey[] $SportList */
        $SportList = \Hokkey::model()->findAll($criteria);
        foreach ($SportList as $Sport)
            $menu[] = [
                'label' => trim(str_replace('Хоккей', '', $Sport->getTitle()), ' .'),
                'url' => ['/sport/hokkey/index', 'sport' => $Sport->getId()],
                'itemOptions' => ['class' => 'menu-item'],
                'linkOptions' => [
                    'data-type' => 'ajax',
                    'data-history' => true,
                ]
            ];

        return $menu;
    }
} 