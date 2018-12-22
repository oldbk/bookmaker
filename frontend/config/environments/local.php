<?php
/**
 * Overrides for all entry points on local development workstations.
 * Note that this is NOT your personal overrides like the passwords.
 * Such changes should end in `/common/overrides/local.php`
 */
return [
    'behaviors' => [
        [
            'class' => '\common\extensions\behaviors\cors\CorsBehavior',
            'route' => '*',
            'allowOrigin' => '*.buker.loc'
        ]
    ],
];
