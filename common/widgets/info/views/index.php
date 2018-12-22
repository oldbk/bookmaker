<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 24.10.2014
 * Time: 16:53
 *
 * @var array $info
 * @var array $sport_cron
 * @var array $result_cron
 */ ?>
<style>
    #widgets-info {
        position: absolute;
        top: 0;
        left: 165px;
    }
    #widgets-info li {
        background: #fff;
        box-shadow: 0 1px 1px rgba(0,0,0,0.1);
        border-radius: 2px;
        min-height: 65px;
        cursor: pointer;
    }
    #widgets-info li.c1 {
        background-color: #00c0ef;
        color: white;
    }
    #widgets-info li.c1:hover {
        background-color: #00a7d0;
    }
    #widgets-info li.c2 {
        background-color: #00a65a;
        color: white;
    }
    #widgets-info li.c2:hover {
        background-color: #008d4c;
    }
    #widgets-info li.c3 {
        background-color: #f39c12;
        color: white;
    }
    #widgets-info li.c3:hover {
        background-color: #db8b0b;
    }
    #widgets-info li.c4 {
        background-color: #dd4b39;
        color: white;
    }
    #widgets-info li.c4:hover {
        background-color: #d33724;
    }
    #widgets-info li.c5 {
        background-color: #605ca8;
        color: white;
    }
    #widgets-info li.c5:hover {
        background-color: #555299;
    }
    #widgets-info li.c6 {
        background-color: #3c8dbc;
        color: white;
    }
    #widgets-info li.c6:hover {
        background-color: #357ca5;
    }

</style>
<div id="widgets-info">
    <ul class="list-inline">
        <li class="c1">
            <div>Лиги: <?= $info['sport_count']; ?></div>
            <div>Событий: <?= $info['find_event'] ?></div>
        </li>
        <li class="c2">
            <div>Новые: <?= $info['create_event_count'] ?></div>
            <div>Обновленные: <?= $info['update_event_count'] ?></div>
            <div>Проблемные: <?= $info['problem_count'] ?></div>
            <div>Играющие: <?= $info['running_event_count'] ?></div>
        </li>
        <li class="c3">
            <div>Старт: <?= date('H:i', $info['time_start']);?></div>
            <div>Финиш: <?= date('H:i', $info['time_end']);?></div>
            <div>Разница: <?= $info['spend_time'] ?>сек.</div>
        </li>
        <li class="c5">
            <div>Крон событий:</div>
            <div>Статус: <?= $sport_cron['status']; ?></div>
            <?php if(!in_array($sport_cron['status'], ['running', 'undefined'])): ?>
                <div>Время: <?= $sport_cron['time'].'сек.' ?></div>
            <?php endif; ?>
            <div>Запись создана: <?= date('H:i', $sport_cron['at'])?></div>
        </li>
        <li class="c6">
            <div>Крон результатов:</div>
            <div>Статус: <?= $result_cron['status']; ?></div>
            <?php if(!in_array($result_cron['status'], ['running', 'undefined'])): ?>
                <div>Время: <?= $result_cron['time'].'сек.' ?></div>
            <?php endif; ?>
            <div>Запись создана: <?= date('H:i', $result_cron['at'])?></div>
        </li>
        <li class="c4">
            <div>Текущее время: <?= date('d.m.Y H:i:s'); ?></div>
            <div>Таймстамп: <?= time(); ?></div>
        </li>
    </ul>
</div>
