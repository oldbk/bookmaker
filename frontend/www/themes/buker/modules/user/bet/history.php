<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 10.02.2015
 * Time: 0:12
 *
 * @var \frontend\components\FrontendController $this
 * @var CPagination $pages
 * @var array $filter
 * @var BettingGroup[] $groups
 * @var UserBetting[] $betting
 * @var SportEvent[] $events
 *
 */ ?>
<div id="content-replacement">
    <div class="">
        <div class="filter" style="display: inline-block;">
            <form data-history="true" method="get" class="center-block ajax"
                  action="<?= Yii::app()->createUrl('/user/bet/history') ?>" id="user-history-filter">
                <ul class="list-inline">
                    <li>
                        <div class="input-daterange input-group datepicker" id="datepicker-filter">
                            <input type="text" class="input-sm form-control field" value="<?= $filter['start']; ?>"
                                   name="Filter[start]"/>
                            <span class="input-group-addon">-</span>
                            <input type="text" class="input-sm form-control col-xs-2 field"
                                   value="<?= $filter['end']; ?>" name="Filter[end]"/>
                        </div>
                    </li>
                    <li>
                        <select name="Filter[bet-type]" class="form-control col-xs-2 field input-sm">
                            <option value="">Все</option>
                            <option
                                value="0" <?= isset($filter['bet-type']) && $filter['bet-type'] == '0' ? 'selected' : '' ?>>
                                Ординар
                            </option>
                            <option
                                value="1" <?= isset($filter['bet-type']) && $filter['bet-type'] == '1' ? 'selected' : '' ?>>
                                Экспресс
                            </option>
                        </select>
                    </li>
                    <li>
                        <input placeholder="Номер ставки" type="text" class="input-sm form-control col-xs-2 field"
                               value="<?= $filter['bet-num']; ?>" name="Filter[bet-num]"/>
                    </li>
                    <li>
                        <label for="Filter_new">Новые</label>
                        <input name="Filter[new]" id="Filter_new" class="field"
                               type="checkbox" <?= isset($filter['new']) && $filter['new'] == 'on' ? 'checked' : '' ?>>
                    </li>
                    <li>
                        <input type="submit" value="Показать" class="btn label-active btn-sm" data-history="true"
                               id="filtered" data-for="user-history-filter" data-type="ajax-submit">
                        <a class="btn label-none btn-sm" data-type="ajax" data-history="true"
                           href="<?= Yii::app()->createUrl('/user/bet/history') ?>">Сбросить</a>
                    </li>
                </ul>
            </form>
        </div>
    </div>
    <div id="user-history-block">
        <?php $this->renderPartial('page/history', ['betting' => $betting, 'events' => $events, 'groups' => $groups]); ?>
    </div>
    <div class="pager-infinity" data-to="user-history-block" data-max="<?= $pages->getPageCount() ?>">
        <?php $this->widget('\common\extensions\pagination\Pagination', [
            'pages' => $pages,
            'placeholder' => true
        ]); ?>
    </div>
</div>