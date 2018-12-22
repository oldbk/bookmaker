<?php
/**
 * Created by PhpStorm.
 * User: me
 *
 * @var \common\components\Controller $this
 * @var array $items
 * @var string $id
 */ ?>

<?= $this->widget(
    'booster.widgets.TbMenu',
    [
        'encodeLabel' => false,
        'htmlOptions' => [
            'class' => 'm-menu left-menu',
        ],
        'items' => $items
    ], true); ?>
