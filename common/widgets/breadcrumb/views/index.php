<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 24.10.2014
 * Time: 16:53
 *
 * @var array $breadcrumbs
 */ ?>

<?php foreach ($breadcrumbs as $key => $breadcrumb): ?>
    <?php
    if($breadcrumb['link'] !== false)
        echo CHtml::link($breadcrumb['label'], $breadcrumb['link']);
    else
        echo CHtml::link($breadcrumb['label'], 'javascript:void(0)');
    if($key < count($breadcrumbs) - 1)
        echo '»';
    ?>
<?php endforeach; ?>