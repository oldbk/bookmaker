<?php
use common\extensions\html\MHtml;

/**
 * Created by PhpStorm.
 *
 * @var BasketballEvent $Event
 */ ?>

<table style="width: auto" class="result-fields">
    <colgroup>
        <col>
        <col width="87px">
        <col width="87px">
        <col width="87px">
        <col width="87px">
    </colgroup>
    <tbody>
    <tr>
        <td></td>
        <td>Период 1</td>
        <td>Период 2</td>
        <td>Период 3</td>
        <td>Результат</td>
    </tr>
    <tr>
        <td><?= $Event->getTeam1() ?></td>
        <td class="center">
            <div class="input-group">
                <?= CHtml::textField(
                    'Result[team_1_part_1]',
                    $Event->getResult()->getTeam1Part1(),
                    ['class' => 'numbers form-control field input-sm result-team1 calc-1']) ?>
                <span class="input-group-addon">голы</span>
            </div>
        </td>
        <td class="center">
            <div class="input-group">
                <?= CHtml::textField(
                    'Result[team_1_part_2]',
                    $Event->getResult()->getTeam1Part2(),
                    ['class' => 'numbers form-control field input-sm result-team1 calc-1']) ?>
                <span class="input-group-addon">голы</span>
            </div>
        </td>
        <td class="center">
            <div class="input-group">
                <?= CHtml::textField(
                    'Result[team_1_part_3]',
                    $Event->getResult()->getTeam1Part3(),
                    ['class' => 'numbers form-control field input-sm result-team1 calc-1']) ?>
                <span class="input-group-addon">голы</span>
            </div>
        </td>
        <td class="center">
            <div class="input-group">
                <?= CHtml::textField(
                    'Result[team_1_result]',
                    $Event->getResult()->getTeam1Result(),
                    ['class' => 'numbers form-control field input-sm', 'readonly' => true]) ?>
                <span class="input-group-addon">голы</span>
            </div>
        </td>
    </tr>
    <tr>
        <td><?= $Event->getTeam2() ?></td>
        <td class="center">
            <div class="input-group">
                <?= CHtml::textField(
                    'Result[team_2_part_1]',
                    $Event->getResult()->getTeam2Part1(),
                    ['class' => 'numbers form-control field input-sm result-team2 calc-1']) ?>
                <span class="input-group-addon">голы</span>
            </div>
        </td>
        <td class="center">
            <div class="input-group">
                <?= CHtml::textField(
                    'Result[team_2_part_2]',
                    $Event->getResult()->getTeam2Part2(),
                    ['class' => 'numbers form-control field input-sm result-team2 calc-1']) ?>
                <span class="input-group-addon">голы</span>
            </div>
        </td>
        <td class="center">
            <div class="input-group">
                <?= CHtml::textField(
                    'Result[team_2_part_3]',
                    $Event->getResult()->getTeam2Part3(),
                    ['class' => 'numbers form-control field input-sm result-team2 calc-1']) ?>
                <span class="input-group-addon">голы</span>
            </div>
        </td>
        <td class="center">
            <div class="input-group">
                <?= CHtml::textField(
                    'Result[team_2_result]',
                    $Event->getResult()->getTeam2Result(),
                    ['class' => 'numbers form-control field input-sm', 'readonly' => true]) ?>
                <span class="input-group-addon">голы</span>
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <label for="Result_is_cancel">Событие отменено?</label> <?= MHtml::checkBox(
                'Result[is_cancel]',
                $Event->getResult()->isCancel(),
                [
                    'class' => 'field',
                    'hiddenOptions' => ['class' => 'field']
                ]); ?>
        </td>
    </tr>
    </tbody>
</table>
