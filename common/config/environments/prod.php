<?php
/**
 * Overrides for all entry points on local development workstations.
 * Note that this is NOT your personal overrides like the passwords.
 * Such changes should end in `/common/overrides/local.php`
 */
return [
    'id' => 'b.oldbk.com',
    'import' => [
        'common.extensions.gii.giix-components.*', // giix components
    ],
    'components' => [
        /*'db' => [
            'connectionString' => 'mysql:host=blogdb.c4c2zvyoc0zt.eu-west-1.rds.amazonaws.com;dbname=bookmaker',
            'username' => 'booker',
            'password' => 'fpoui0943flFOW83',
            'enableParamLogging' => false,
            'enableProfiling' => false,
            'charset' => 'utf8',
        ],*/
        'db' => [
            'connectionString' => 'mysql:host=88.198.205.122;dbname=bookerdb',
            'username' => 'bookerdb',
            'password' => '7MDTcs0bkSug',
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
            'loginUrl' => 'http://oldbk.com',
            'identityCookie' => [
                'path' => '/',
                'domain' => '.b.oldbk.com',
            ]
        ],
        'session' => [
            'class' => 'CHttpSession',
            'sessionName' => 'buker_oldbk',
            'cookieMode' => 'allow',
            'cookieParams' => [
                'path' => '/',
                'domain' => '.b.oldbk.com'
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
            'port' => 443,     // default is 3001, should be integer,
            'origin' => '*:*',
            'allowedServerAddresses' => ['88.198.205.124', '136.243.242.218', '136.243.242.217', '54.228.184.100', '5.9.138.245']
        ],
    ],
    'params' => [
        'static_domain' => 'http://i.b.oldbk.com'
    ]
];
