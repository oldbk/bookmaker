<?php
/**
 * Overrides for all entry points on local development workstations.
 * Note that this is NOT your personal overrides like the passwords.
 * Such changes should end in `/common/overrides/local.php`
 */
return [
    'name' => 'Букмекер - DEV',
    'id' => 'buker.phptd.ru',
    'import' => [
        'common.extensions.gii.giix-components.*', // giix components
    ],
    'components' => [
        'db' => [
            'connectionString' => 'mysql:host=88.198.205.122;dbname=bookerdb',
            'username' => 'bookerdb',
            'password' => 'NLUdH9AQga76cNy8bMA6',
            'enableParamLogging' => false,
            'enableProfiling' => false,
            'charset' => 'utf8',
        ],
        'cache' => [
            'class' => 'system.caching.CMemCache',
            'useMemcached' => true,
            'servers' => [
                [
                    'host' => '88.198.205.125',
                    'port' => 11211,
                    'weight' => 60,
                ],
            ],
        ],
        'mongodb' => [
            'class' => 'EMongoClient',
            'server' => 'mongodb://localhost:27017',
            'db' => 'bookmaker'
        ],
        'user' => [
            'loginUrl' => 'http://oldbk.booker.local',
            'identityCookie' => [
                'path' => '/',
                'domain' => '.oldbk.booker.local',
            ]
        ],
        'session' => [
            'class' => 'CHttpSession',
            'sessionName' => 'buker_oldbk',
            'cookieMode' => 'allow',
            'cookieParams' => [
                'path' => '/',
                'domain' => '.oldbk.booker.local'
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
        'static_domain' => 'http://s.buker.phptd.ru',
        'env' => 'dev',
    ]
];