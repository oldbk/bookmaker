<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 09.02.2015
 * Time: 23:23
 *
 * @var SportEvent[] $SportEventList
 * @var array $ProblemByEvent
 * @var Pages $pages
 * @var SportEventProblem $Problem
 * @var User[] $UserList
 * @var \frontend\components\FrontendController $this
 */ ?>

<div class="" id="content-replacement">
    <div class="filter">
        <form data-history="true" method="get" class="center-block ajax"
              action="<?= Yii::app()->createUrl('/admin/problem/index') ?>" id="all-betting-filter">
            <ul class="list-inline">
                <li>
                    <label>Завершенные</label>
                    <input name="Filter[finish]" class="field"
                           type="checkbox" <?= isset($filter['finish']) && $filter['finish'] == 'on' ? 'checked' : '' ?>>
                </li>
                <li>
                    <a class="btn label-active btn-sm" data-history="true" id="filtered" data-for="all-betting-filter"
                       data-type="ajax-submit">Показать</a>
                    <a class="btn label-none btn-sm" href="<?= Yii::app()->createUrl('/admin/problem/index') ?>">Сбросить</a>
                </li>
            </ul>
        </form>
    </div>
    <a class="btn label-none btn-sm" href="<?= Yii::app()->createUrl('/admin/problem/list') ?>">Управление</a>
    <div class="grid-block">
        <?php $this->widget('\common\extensions\pagination\Pagination', [
            'pages' => $pages,
        ]); ?>
        <table id="in" class="table list">
            <colgroup>
                <col>
                <col>
                <col>
                <col width="150px">
            </colgroup>
            <thead>
            <tr>
                <th class="center">#</th>
                <th class="center">Дата</th>
                <th class="center">Событие</th>
                <th class="center">Кол-во ошибок/ставок</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($SportEventList as $SportEvent): ?>
                <tr>
                    <td><?= $SportEvent->getId() ?></td>
                    <td><?= date('d.m.Y H:i', $SportEvent->getDateInt()) ?></td>
                    <td><?= $SportEvent->sport->getTitle() . " " . $SportEvent->getTeam1() . " - " . $SportEvent->getTeam2() ?></td>
                    <td class="center">
                        <span class="badge"><?= $SportEvent->getProblemCount() ?>/<?= $SportEvent->betCount ?></span>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <table>
                            <colgroup>
                                <col width="150px">
                                <col>
                                <col>
                                <col width="50px">
                            </colgroup>
                            <tbody>
                            <?php foreach ($ProblemByEvent[$SportEvent->getId()] as $Problem): ?>
                                <tr class="transparent">
                                    <td class="left">
                                        <label class="badge <?= $Problem->getIsResolved() ? 'green' : 'red'; ?>">
                                            <?= $Problem->getProblemTypeLabel(); ?>
                                        </label>
                                    </td>
                                    <td class="left">
                                        <i><?= $Problem->getDescription(); ?></i>
                                    </td>
                                    <td class="right">
                                        <?php if ($Problem->getIsResolved()): ?>
                                            <strong>
                                                <?= $Problem->getResolverId() ? $UserList[$Problem->getResolverId()]->buildLogin() : 'Авто'; ?>
                                            </strong>
                                            [<?= date('d.m.Y H:i', $Problem->getUpdateAt()) ?>]
                                        <?php endif; ?>
                                    </td>
                                    <td class="right">
                                        <?php $this->widget('common\widgets\buttons\ButtonsWidget', ['buttons' => [
                                            'accept' => [
                                                'visible' => !$Problem->getIsResolved(),
                                                'htmlOptions' => [
                                                    'data-type' => 'ajax',
                                                    'data-link' => Yii::app()->createUrl('/admin/problem/resolve_' . $Problem->getProblemType(), ['problem_id' => $Problem->getId()]),
                                                    'data-toggle' => 'tooltip',
                                                    'title' => 'Обработать',
                                                    'class' => 'glyphicon glyphicon-ok pointer green'
                                                ]
                                            ],
                                            'decline' => [
                                                'visible' => !$Problem->getIsResolved(),
                                                'htmlOptions' => [
                                                    'data-type' => 'ajax',
                                                    'data-link' => Yii::app()->createUrl('/admin/problem/ignore', ['problem_id' => $Problem->getId()]),
                                                    //'data-confirm' => 'true',
                                                    //'data-confirm-text' => 'Вы уверены, что хотите игнорировать эту проблему и в предь ее не фиксировать?',
                                                    'data-toggle' => 'tooltip',
                                                    'title' => 'Игнорировать',
                                                    'class' => 'glyphicon glyphicon-remove pointer red'
                                                ]
                                            ],
                                        ]]) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php $this->widget('\common\extensions\pagination\Pagination', [
            'pages' => $pages,
        ]); ?>
    </div>
</div>