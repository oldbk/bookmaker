<?php
/**
 * Overrides for all entry points on local development workstations.
 * Note that this is NOT your personal overrides like the passwords.
 * Such changes should end in `/common/overrides/local.php`
 */
return [
    'name' => 'Букмекер - LOCAL',
    'id' => 'buker.local',
    'import' => [
        'common.extensions.gii.giix-components.*', // giix components
    ],
    'components' => [
        'db' => [
            'connectionString' => 'mysql:host=localhost;dbname=buker',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'enableParamLogging' => true,
            'enableProfiling' => true,
        ],
        /*'db' => [
            'connectionString' => 'mysql:host=oldbkfastdb.c4c2zvyoc0zt.eu-west-1.rds.amazonaws.com;dbname=bookmaker',
            'username'         => 'booker',
            'password'         => 'fpoui0943flFOW83',
            'class'            => 'CDbConnection',
            'charset'          => 'utf8',
            'enableParamLogging' => true,
            'enableProfiling' => true,
        ],*/
        "cache" => [
            "class" => "redis_package.ARedisCache"
        ],
        'redis' => [
            "class" => "redis_package.ARedisConnection",
            "database" => 1,
            "prefix" => "Yii.redis.",
            "hostname" => "localhost",
            "port" => 6379,
        ],
        'mongodb' => [
            'class' => 'EMongoClient',
            'server' => 'mongodb://localhost:27017',
            'db' => 'dev_buker'
        ],
        'user' => [
            'loginUrl' => 'http://buker.local',
            'identityCookie' => [
                'path' => '/',
                'domain' => '.buker.local',
            ]
        ],
        'session' => [
            'class' => 'redis_package.ARedisSession',
            'sessionName' => 'buker_oldbk',
            'cookieMode' => 'allow',
            'cookieParams' => [
                'path' => '/',
                'domain' => '.buker.local'
            ],
        ],
        'urlManager' => [
            'cacheID' => false,
            'rules' => [
            ]
        ],
        'log' => [
            'class' => 'CLogRouter',
            'routes' => [
                'logFile' => [
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning, trace, info',
                    'filter' => 'CLogFilter'
                ],
            ]
        ],
        'nodeSocket' => [
            'sessionVarName' => 'buker_oldbk',
            'host' => 'ws.b.oldbk.com',  // default is 127.0.0.1, can be ip or domain name, without http
            'ip' => '0.0.0.0',
            'port' => 8880,     // default is 3001, should be integer,
            'origin' => '*:*',
            'allowedServerAddresses' => ['136.243.242.218', '136.243.242.217', '54.228.184.100', '5.9.138.245']
        ],
    ],
    'params' => [
        'static_domain' => 'http://static.buker.local',
        'env' => 'local',
    ]
];