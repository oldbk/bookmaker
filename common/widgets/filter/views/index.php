<?php
use \common\interfaces\iStatus;
/**
 * Created by PhpStorm.
 * User: me
 * Date: 24.10.2014
 * Time: 16:53
 *
 * @var array $breadcrumbs
 * @var array $filter
 * @var string $clearUrl
 * @var string $url
 */ ?>
<div class="filter">
    <div class="panel panel-default">
        <div class="panel-heading" style="padding: 0;">
            <a style="padding: 10px 15px;display: block;" data-toggle="collapse" data-target="#filter-body" href="javascript:void(0);">
                Фильтрация
                <div class="pull-right">
                    <span class="caret"></span>
                </div>
            </a>
        </div>
        <div class="panel-body collapse" id="filter-body">
            <form class="ajax" method="get" action="<?= $url ?>" id="line-filter">
                <ul class="list-inline">
                    <li>
                        <label for="Filter_sport_type">Вид спорта: </label>
                        <div>
                            <select name="Filter[sport_type][]" id="Filter_sport_type" class="form-control col-xs-2 field input-sm select2-tag" multiple>
                                <option value="1" <?= $filter['sport_type'] == 1 ? 'selected' : ''; ?>>Футбол</option>
                                <option value="2" <?= $filter['sport_type'] == 2 ? 'selected' : ''; ?>>Теннис</option>
                                <option value="3" <?= $filter['sport_type'] == 3 ? 'selected' : ''; ?>>Баскетбол</option>
                                <option value="4" <?= $filter['sport_type'] == 4 ? 'selected' : ''; ?>>Хоккей</option>
                            </select>
                        </div>
                    </li>
                    <li>
                        <label for="Filter_event_status">Статус: </label>
                        <div>
                            <select name="Filter[event_status][]" id="Filter_event_status" class="form-control col-xs-2 field input-sm select2-tag" multiple>
                                <option value="<?= iStatus::STATUS_NEW ?>">Новые</option>
                                <option value="<?= iStatus::STATUS_FINISH ?>">Завершенные</option>
                                <option value="<?= iStatus::STATUS_ENABLE ?>">Включенные</option>
                                <option value="<?= iStatus::STATUS_DISABLE ?>">Выключенные</option>
                                <option value="<?= iStatus::STATUS_LIVE ?>">LIVE</option>
                            </select>
                        </div>
                    </li>
                    <li>
                        <label for="">Даты: </label>
                        <div class="input-daterange input-group datepicker" id="datepicker-filter">
                            <input type="text" class="input-sm form-control field" value="<?= $filter['start']; ?>" name="Filter[start]" />
                            <span class="input-group-addon">-</span>
                            <input type="text" class="input-sm form-control col-xs-2 field" value="<?= $filter['end']; ?>" name="Filter[end]" />
                        </div>
                    </li>
                    <li>
                        <label for="Filter_liga">Лига: </label>
                        <div>
                            <select style="width: 300px;" name="Filter[liga][]" data-link="<?= Yii::app()->createUrl('/ac/line') ?>" id="Filter_liga" class="form-control field select2-remote" multiple>
                            </select>
                        </div>
                    </li>
                </ul>
                <div class="center">
                    <input type="submit" class="btn label-active btn-sm" id="filtered" data-for="line-filter" data-type="ajax-submit" value="Показать">
                    <a class="btn label-none btn-sm" data-type="ajax" data-link="<?= $clearUrl ?>">Сбросить</a>
                </div>
            </form>
        </div>
    </div>
</div>