<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 24.10.2014
 * Time: 16:53
 *
 * @var array $buttons
 * @var string $wrapTag
 */ ?>

<?php foreach($buttons as $button): ?>
    <?= CHtml::tag($wrapTag, $button['htmlOptions'], $button['label']) ?>
<?php endforeach; ?>