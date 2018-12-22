<?php
/**
 * Created by PhpStorm.
 * User: ice
 * Date: 16.09.2015
 * Time: 2:03
 *
 * @var array $messages
 */ ?>

<ul>
    <?php foreach($messages as $message): ?>
        <li><?= $message ?></li>
    <?php endforeach; ?>
</ul>