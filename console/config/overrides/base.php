<?php
/**
 * Overrides for configuration when we're in console application, i. e., in context of `yiic`.
 */
return [
    // Changing `application` path alias to point at `/console` subdirectory
    'basePath' => 'console',
    'behaviors' => [
        'newRelic' => [
            'class' => 'common.extensions.yii-newrelic.behaviors.YiiNewRelicConsoleAppBehavior',
        ],
    ],
    'commandMap' => [
        'migrate' => [
            'class' => 'system.cli.commands.MigrateCommand',
            'migrationPath' => 'application.migrations',
            'templateFile' => 'application.migrations.template.template'
        ],
        'node-socket' => [
            'class' => 'common.extensions.yii-node-socket.lib.php.NodeSocketCommand',
            'pathToNodeJs' => '/usr/bin/nodejs'
        ]
    ],
];