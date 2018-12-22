<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 10.09.2014
 * Time: 14:15
 */

namespace common\extensions\behaviors\menu\menu;

use Yii;
class UMenu
{
    /**
     * @return array
     */
    public function run()
    {
        if(!Yii::app()->getUser()->isAdmin())
            return [];

        $criteria = new \CDbCriteria();
        $criteria->scopes = ['haveProblem', 'nTrash'];
        $criteria->addCondition('(select count(id) from user_betting ub where ub.event_id = `t`.id) > 0');
        $error_count = \SportEvent::model()->count($criteria);

        $problem_label = 'Проблемы';
        if($error_count)
            $problem_label = sprintf('Проблемы (<span style="color: red;">%d</span>)', $error_count);

        $criteria = new \CDbCriteria();
        $criteria->addCondition('`t`.status = :status');
        $criteria->with = ['user'];
        $criteria->params = [':status' => \UserOutputRequest::STATUS_NEW];
        $output_request = \UserOutputRequest::model()->count($criteria);

        $output_label = 'Запросы на вывод';
        if($output_request)
            $output_label = sprintf('Запросы на вывод (<span style="color: red;">%d</span>)', $output_request);


        $menu = [
            [
                'label' => 'Админка',
                'itemOptions' => ['class' => 'menu-title'],
                'template' => '<span class="glyphicon glyphicon-cog pull-left"></span>{menu}'
            ],
            [
                'label' => 'Посмотреть линии',
                'url' => ['/admin/line/index'],
                'itemOptions' => [
                    'class' => 'menu-item',
                ],
                'linkOptions' => [
                    'data-type' => 'ajax',
                    'data-history' => true,
                ]
            ],
            [
                'label' => 'Посмотреть все ставки',
                'url' => ['/admin/event/all-betting'],
                'itemOptions' => [
                    'class' => 'menu-item',
                ],
                'linkOptions' => [
                    'data-type' => 'ajax',
                    'data-history' => true,
                ]
            ],
            [
                'label' => $problem_label,
                'url' => ['/admin/problem/index'],
                'itemOptions' => [
                    'class' => 'menu-item',
                ],
                'linkOptions' => [
                    'data-type' => 'ajax',
                    'data-history' => true,
                ]
            ],
            [
                'label' => 'Tools',
                'url' => ['/admin/tools/index'],
                'itemOptions' => [
                    'class' => 'menu-item',
                ],
                'linkOptions' => [
                    'data-type' => 'ajax',
                    'data-history' => true,
                ]
            ],
            [
                'label' => 'Корзина',
                'url' => ['/admin/trash/index'],
                'itemOptions' => [
                    'class' => 'menu-item',
                ],
                'linkOptions' => [
                    'data-type' => 'ajax',
                    'data-history' => true,
                ]
            ],
            [
                'label' => 'Админ лог',
                'url' => ['/admin/log/index'],
                'itemOptions' => [
                    'class' => 'menu-item',
                ],
                'linkOptions' => [
                    'data-type' => 'ajax',
                    'data-history' => true,
                ]
            ],
            [
                'label' => 'Настройки',
                'url' => ['/admin/settings/index'],
                'itemOptions' => [
                    'class' => 'menu-item',
                ],
                'linkOptions' => [
                    'data-type' => 'ajax',
                    'data-history' => true,
                ]
            ],
            [
                'label' => 'Пользователи',
                'url' => ['/admin/user/index'],
                'itemOptions' => [
                    'class' => 'menu-item',
                ],
                'linkOptions' => [
                    'data-type' => 'ajax',
                    'data-history' => true,
                ]
            ],
            [
                'label' => 'Акции',
                'url' => ['/admin/akcii/index'],
                'itemOptions' => [
                    'class' => 'menu-item',
                ],
                'linkOptions' => [
                    'data-type' => 'ajax',
                    'data-history' => true,
                ]
            ],
            [
                'label' => 'Статистика',
                'url' => ['/admin/stats/index'],
                'itemOptions' => [
                    'class' => 'menu-item',
                ],
                'linkOptions' => [
                    'data-type' => 'ajax',
                    'data-history' => true,
                ]
            ],
            [
                'label' => 'Команды',
                'url' => ['/admin/team/index'],
                'itemOptions' => [
                    'class' => 'menu-item',
                ],
                'linkOptions' => [
                    'data-type' => 'ajax',
                    'data-history' => true,
                ]
            ],
            [
                'label' => $output_label,
                'url' => ['/admin/output/index'],
                'itemOptions' => [
                    'class' => 'menu-item',
                ],
                'linkOptions' => [
                    'data-type' => 'ajax',
                    'data-history' => true,
                ]
            ],
            [
                'label' => 'История вывод',
                'url' => ['/admin/output/out'],
                'itemOptions' => [
                    'class' => 'menu-item',
                ],
                'linkOptions' => [
                    'data-type' => 'ajax',
                    'data-history' => true,
                ]
            ],
            [
                'label' => 'FAQ',
                'url' => ['/admin/docs/index'],
                'itemOptions' => [
                    'class' => 'menu-item',
                ],
                'linkOptions' => [
                    'data-type' => 'ajax',
                    'data-history' => true,
                ]
            ],
            [
                'label' => 'Статические страницы',
                'url' => ['/admin/page/index'],
                'itemOptions' => [
                    'class' => 'menu-item',
                ],
                'linkOptions' => [
                    'data-type' => 'ajax',
                    'data-history' => true,
                ]
            ],
            [
                'label' => 'Supervisor',
                'url' => 'http://88.198.205.124:9211/',
                'itemOptions' => [
                    'class' => 'menu-item',
                ],
                'linkOptions' => [
                    'target' => '_blank',
                ]
            ],
        ];

        if(Yii::app()->getUser()->getName() == 'Байт') {
            $criteria = new \EMongoCriteria();
            $criteria->addCondition('is_new', true);
            $error_count = \Critical::model()->count($criteria);
            $label = 'Критические ошибки';
            if($error_count)
                $label = sprintf('Критические ошибки (<span style="color: red;">%d</span>)', $error_count);

            $menu[] = [
                'label' => 'Анализ лога',
                'url' => ['/admin/log/logfile'],
                'itemOptions' => [
                    'class' => 'menu-item'
                ],
                /*'linkOptions' => [
                    'data-type' => 'ajax',
                    'data-history' => true,
                ]*/
            ];

            $menu[] = [
                'label' => $label,
                'url' => ['/admin/problem/critical'],
                'itemOptions' => [
                    'class' => 'menu-item',
                ],
                'linkOptions' => [
                    'data-type' => 'ajax',
                    'data-history' => true,
                ]
            ];
        }

        return $menu;
    }
} 