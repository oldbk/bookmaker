<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 10.02.2015
 * Time: 0:12
 *
 * @var \frontend\components\FrontendController $this
 * @var BettingGroup[] $groups
 * @var UserBetting[] $betting
 * @var SportEvent[] $events
 * @var CPagination $pages
 * @var array $filter
 *
 */ ?>
<div class="" id="content-replacement">
    <div class="filter">
        <form data-history="true" method="get" class="center-block ajax"
              action="<?= Yii::app()->createUrl('/admin/event/all-betting') ?>" id="all-betting-filter">
            <ul class="list-inline">
                <li>
                    <input data-source-hidden-for="all-betting-user" placeholder="Начните вводить логин"
                           name="Filter[user_login]" value="<?= $filter['user_login'] ?>"
                           data-source="<?= Yii::app()->createUrl('/user/user/auto') ?>"
                           class="form-control input-sm field auto-source-user-list" type="text">
                    <input data-source-hidden="all-betting-user" type="hidden" name="Filter[user]"
                           value="<?= $filter['user'] ?>" class="field">
                </li>
                <li>
                <li>
                    <div class="input-daterange input-group datepicker" id="datepicker-filter">
                        <input type="text" class="input-sm form-control field" value="<?= $filter['start']; ?>"
                               name="Filter[start]"/>
                        <span class="input-group-addon">-</span>
                        <input type="text" class="input-sm form-control col-xs-2 field" value="<?= $filter['end']; ?>"
                               name="Filter[end]"/>
                    </div>
                </li>
                <li>
                    <input placeholder="Номер события" type="text" class="input-sm form-control field"
                           value="<?= $filter['event_id']; ?>" name="Filter[event_id]"/>
                </li>
                <li>
                    <input placeholder="Номер ставки" type="text" class="input-sm form-control field"
                           value="<?= $filter['bet_id']; ?>" name="Filter[bet_id]"/>
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
                    <select name="Filter[price-type]" class="form-control col-xs-2 field input-sm">
                        <option value="">Все</option>
                        <option
                            value="0" <?= $filter['price-type'] != '' && isset($filter['price-type']) && $filter['price-type'] == \common\interfaces\iPrice::TYPE_KR ? 'selected' : '' ?>>
                            КР
                        </option>
                        <option
                            value="1" <?= $filter['price-type'] != '' && isset($filter['price-type']) && $filter['price-type'] == \common\interfaces\iPrice::TYPE_EKR ? 'selected' : '' ?>>
                            ЕКР
                        </option>
                    </select>
                </li>
                <li>
                    <br>
                    <label>Завершенные</label>
                    <input name="Filter[finish]" class="field"
                           type="checkbox" <?= isset($filter['finish']) && $filter['finish'] == 'on' ? 'checked' : '' ?>>
                </li>
                <li>
                    <br>
                    <label>Не показывать отозванные</label>
                    <input name="Filter[no_refund]" class="field"
                           type="checkbox" <?= isset($filter['no_refund']) && $filter['no_refund'] == 'on' ? 'checked' : '' ?>>
                </li>
                <li>
                    <br>
                    <?= CHtml::dropDownList('Filter[ratio_type]', isset($filter['ratio_type']) ? $filter['ratio_type'] : '', CMap::mergeArray([0 => 'Все'], SportEvent::getRatioLabelDrop()), [
                        'class' => 'form-control col-xs-2 field input-sm',
                    ]) ?>
                </li>
                <li>
                    <br>
                    <input type="submit" class="btn label-active btn-sm" data-history="true" id="filtered"
                           data-for="all-betting-filter" data-type="ajax-submit" value="Показать">
                    <a class="btn label-none btn-sm" href="<?= Yii::app()->createUrl('/admin/event/all-betting') ?>">Сбросить</a>
                    <a class="btn label-none btn-sm" data-type="ajax-submit" data-for="all-betting-filter"
                       data-link="<?= Yii::app()->createUrl('/admin/event/calc-betting') ?>"
                       href="<?= Yii::app()->createUrl('/admin/event/calc-betting') ?>">Расчитать</a>
                </li>
            </ul>
        </form>
    </div>
    <ul>
        <li>Всего найдено: <?= $pages->itemCount; ?><span id="betting-calc"></span></li>
    </ul>
    <?php foreach ($groups as $group): ?>
        <?php $this->renderPartial('_rate_item', [
            'historyList' => $betting[$group->getId()],
            'BettingGroup' => $group,
            'events' => $events[$group->getId()]
        ]) ?>
    <?php endforeach; ?>
    <?php $this->widget('\common\extensions\pagination\Pagination', [
        'pages' => $pages,
        'displayFirstAndLast' => true,
    ]); ?>
</div>