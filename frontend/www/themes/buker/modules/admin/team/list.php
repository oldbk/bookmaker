<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 09.02.2015
 * Time: 23:23
 *
 * @var CActiveDataProvider $dataProvider
 * @var \frontend\components\FrontendController $this
 * @var TeamAliasNew[] $newAliases
 */ ?>

<div class="" id="content-replacement">
    <?php if ($newAliases): ?>
        <div class="new-aliases-block">
            <a href="javascript:void(0);" onclick="$('#new-alias-list').toggle();"><h3>Новые Алиасы</h3></a>
            <table id="new-alias-list" style="display: none;" class="list table">
                <colgroup>
                    <col width="100%">
                    <col width="20px">
                </colgroup>
                <tbody>
                <?php foreach ($newAliases as $Alias): ?>
                    <tr data-alias-new="<?= $Alias->getId() ?>">
                        <td><?= $Alias->getTitle(); ?></td>
                        <td class="buttons" nowrap>
                        <span
                            data-type="ajax"
                            data-link="<?= Yii::app()->createUrl('/admin/team/accept', ['alias_id' => $Alias->getId()]) ?>"
                            class="glyphicon glyphicon-ok pointer green"
                            data-toggle="tooltip"
                            title="Принять"></span>
                        <span
                            data-type="ajax"
                            data-link="<?= Yii::app()->createUrl('/admin/team/delete', ['alias_id' => $Alias->getId()]) ?>"
                            class="glyphicon glyphicon-remove pointer red"
                            data-toggle="tooltip"
                            title="Удалить"></span>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
    <div class="grid-block">
        <?php $this->widget(
            'booster.widgets.TbGridView',
            [
                'id' => 'team-list',
                'dataProvider' => $dataProvider,
                'template' => "{pager}{items}{pager}",
                'itemsCssClass' => 'list',
                'pager' => [
                    'class' => 'common\extensions\pagination\Pagination'
                ],
                'ajaxUpdate' => false,
                'columns' => [
                    [
                        'name' => 'id',
                        'header' => '#',
                        'htmlOptions' => ['style' => 'width: 30px'],
                        'class' => 'common\extensions\grid\MDataColumn',
                    ],
                    [
                        'name' => 'title',
                        'htmlOptions' => ['nowrap' => 'true'],
                        'class' => 'common\extensions\grid\MDataColumn',
                    ],
                    [
                        'header' => 'Алиасы',
                        'type' => 'raw',
                        'value' => '$data->getAliases()',
                        'class' => 'common\extensions\grid\MDataColumn',
                    ],
                    [
                        'name' => 'create_at',
                        'value' => 'date("d.m.Y H:i", $data->getCreateAt())',
                        'class' => 'common\extensions\grid\MDataColumn',
                    ],
                ],
            ]
        ); ?>
    </div>
</div>