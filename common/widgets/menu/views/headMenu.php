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
        'htmlOptions' => [
        ],
        'items' => $items
    ], true); ?>
