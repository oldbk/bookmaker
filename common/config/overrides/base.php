<?php
/**
 * Configuration parameters common to all entry points.
 */

//9658f8edf6f59831b07dd8137902ddd9b391f CLOUDFLARE API
return [
    'name' => 'Букмекер',
    'preload' => ['log', 'bootstrap'],
    'aliases' => [
        'booster' => 'vendor.clevertech.yii-booster.src',
        'redis_package' => 'vendor.codemix.yiiredis',
    ],
    'import' => [
        'common.lib.*',
        'common.helpers.*',
        'common.components.*',
        'common.components.url.*',
        'common.models.*',
        'common.models.event.*',
        'common.models.bet.*',
        'common.models.sport.*',
        'common.models.balance.*',
        'common.models.custom.*',
        'common.models.oldbk.*',
        'common.models.mongo.*',
        'common.interfaces.*',
        'common.parser.*',
        'vendor.zhdanovartur.yii-easyimage.EasyImage',
        'vendor.sammaye.mongoyii.*',
        'vendor.sammaye.mongoyii.validators.*',
        'vendor.sammaye.mongoyii.behaviors.*',
        'vendor.sammaye.mongoyii.util.*',
        'booster.components.*',
        'booster.helpers.*',
        'booster.widgets.*',
    ],
    'sourceLanguage' => 'ru_RU',
    'language' => 'ru',
    'components' => [
        'nodeSocket' => [
            'class' => 'common.extensions.yii-node-socket.lib.php.NodeSocket',
        ],
        /*'viewRenderer' => [
            'class' => 'vendor.yiiext.twig-renderer.ETwigViewRenderer',
            'twigPathAlias' => 'vendor.twig.twig.lib.Twig',

            // All parameters below are optional, change them to your needs
            'fileExtension' => '.twig',
            'options' => [
                'autoescape' => false,
                'debug' => true
            ],
            'extensions' => [
                //'My_Twig_Extension',
            ],
            'globals' => [
                'html' => 'CHtml',
                'Convert' => '\common\helpers\Convert',
                'iStatus' => '\common\interfaces\iStatus'
            ],
            'functions' => [
                'rot13' => 'str_rot13',
                'DataToViewFactory' => '\common\factories\DataToViewFactory::factory'
            ],
            'filters' => [
                'jencode' => 'CJSON::encode',
                'palias' => 'Yii::getPathOfAlias',
            ],
        ],*/
        'breadcrumbs' => [
            'class' => '\common\components\Breadcrumbs'
        ],
        'checker' => [
            'class' => '\common\components\CheckValidOutput'
        ],
        'request' => [
            'class' => '\common\components\Request',
            'enableCsrfValidation' => false,
            //'baseUrl' => 'http://'.$_SERVER['HTTP_HOST']
        ],
        'jsTrans' => [
            'class' => 'ext.JsTrans.JsTrans',
            'categories' => ['app', 'labels'], // the categories to be made available
            'languages' => ['ru'] // the languages to be made available
        ],
        'clientScript' => [
            'class' => '\common\components\NClientScript',
            'packages' => [
                'jquery' => [
                    'baseUrl' => '//ajax.googleapis.com/ajax/libs/jquery/1/',
                    'js' => ['jquery.min.js'],
                ]
            ],
        ],
        'oldbk' => [
            'class' => '\common\components\oldbk\Oldbk',
            'auth_link' => 'http://capitalcity.oldbk.com/book_login.php',
            'info_link' => 'http://capitalcity.oldbk.com/blog_check.php',
            'bank_info_link' => 'http://capitalcity.oldbk.com/api_list_bank.php',
            'money_info_link' => 'http://capitalcity.oldbk.com/api_list_money.php',
            'gold_info_link' => 'http://capitalcity.oldbk.com/api_list_gold.php',
            'bank_auth_link' => 'http://capitalcity.oldbk.com/api_list_bank_login.php',
            'cr_operation_link' => 'http://capitalcity.oldbk.com/api_money.php',
            'ekr_operation_link' => 'http://capitalcity.oldbk.com/api_bank_money.php',
            'gold_operation_link' => 'http://capitalcity.oldbk.com/api_gold.php',
            'api_key' => 'I9RdXHeFYNlufui3TrRZ38U8',
        ],
        'static' => [
            'class' => '\common\components\StaticContent'
        ],
        'parimatch' => [
            'class' => '\common\components\Parimatch'
        ],
        'sport' => [
            'class' => '\common\components\updater\Sport'
        ],
        'ih' => [
            'class' => '\common\components\CImageHandler',
        ],
        'bootstrap' => [
            'class' => 'booster.components.Booster',
            //'coreCss' => false,
            //'bootstrapCss' => false,
            //'responsiveCss' => false,
            //'yiiCss' => false,
        ],
        'user' => [
            'allowAutoLogin' => true,
            'autoRenewCookie' => true,
            'returnUrl'=> ['/site/index'],
            'class' => '\common\components\WebUser',
        ],
        'authManager' => [
            'class' => 'CDbAuthManager',
            'connectionID' => 'db',
            'defaultRoles' => ['authenticated', 'guest'],
        ],
        'imageUpload' => [
            'class' => '\common\components\ImageUpload'
        ],
        'ajax' => [
            'class' => '\common\components\Ajax'
        ],
        'curl' => [
            'class' => 'vendor.hackerone.curl.Curl',
            'options' => [
                CURLOPT_VERBOSE => false,
                CURLOPT_COOKIEJAR => ROOT_DIR.'/cookie/curl.txt',
                CURLOPT_COOKIEFILE => ROOT_DIR.'/cookie/curl.txt',
                CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.132 Safari/537.36',
                CURLOPT_REFERER => 'http://parimatch.com',
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_RETURNTRANSFER => true,
            ]
        ],
        'phantom' => [
            'class' => '\common\components\Curl',
            'pathToConfig' => realpath(__DIR__).'/phantom.conf.json'
        ],
        'mongodb' => [
            'class' => 'EMongoClient',
            'db' => 'buker'
        ],
        'db' => [
            //'schemaCachingDuration' => PRODUCTION_MODE ? 86400000 : 0, // 86400000 == 60*60*24*1000 seconds == 1000 days
            'schemaCachingDuration' => 0, // 86400000 == 60*60*24*1000 seconds == 1000 days
            'enableParamLogging' => !PRODUCTION_MODE,
            'charset' => 'utf8',
            'tablePrefix' => ''
        ],
        'urlManager' => [
            'class' => 'UrlManager',
            'urlFormat' => 'path',
            'showScriptName' => false,
            'urlSuffix' => '.html',
            'rules' => [
                ['admin/line/index',        'pattern' => '/admin/line/index'],
                ['admin/line/events',       'pattern' => '/admin/line/<line_id:.*>/events'],
                ['admin/event/accept',      'pattern' => '/admin/event/<event_id:.*>/accept',                   'urlSuffix' => '.ajax'],
                ['admin/event/control',     'pattern' => '/admin/event/<event_id:.*>/<line_id:.*>/control',     'urlSuffix' => '.ajax'],
                ['admin/event/decline',     'pattern' => '/admin/event/<event_id:.*>/decline',                  'urlSuffix' => '.ajax'],
                ['admin/event/history',     'pattern' => '/admin/<line_id:\d+>/event/<event_id:.*>/history'],

                ['admin/settings/index',    'pattern' => '/admin/settings/index'],
                ['admin/history/index',     'pattern' => '/admin/history/index'],

                ['line/events',             'pattern' => '/line/<line_id:\d+>/list'],

                ['user/finance/refund',     'pattern' => '/user/<bet_id:\d+>/refund'],


                ['user/finance/in_voucher', 'pattern' => '/user/finance/in/voucher',            'urlSuffix' => '.ajax'],
                ['user/finance/in_erk',     'pattern' => '/user/finance/in/erk',                'urlSuffix' => '.ajax'],
                ['user/finance/in_kr',      'pattern' => '/user/finance/in/kr',                 'urlSuffix' => '.ajax'],
                ['user/finance/out_voucher','pattern' => '/user/finance/out/voucher',           'urlSuffix' => '.ajax'],
                ['user/finance/out_erk',    'pattern' => '/user/finance/out/erk',               'urlSuffix' => '.ajax'],
                ['user/finance/out_kr',     'pattern' => '/user/finance/out/kr',                'urlSuffix' => '.ajax'],

                ['admin/stats/charts',      'pattern' => '/admin/stats/charts',                 'urlSuffix' => '.json'],
                //['<_m>/<_c>/<_a>',          'pattern' => '<_m>/<_c>/<_a>'],
                //['<_c>/<_a>',               'pattern' => '<_c>/<_a>'],
            ]
        ],
        'messages' => [
            'basePath' => 'common/messages'
        ],
        'newRelic' => [
            'class' => 'common.extensions.yii-newrelic.YiiNewRelic',
        ],
    ],
    'params' => [
        'mailer' => [
            'email' => 'no-replay@oldbk.com',
            'name' => 'no-replay'
        ],
        'pages' => [
            'image' => 10,
            'video' => 10
        ],
        'sizes' => [
            'avatar' => [
                'preview' => [
                    'width' => 200,
                    'height' => 200
                ]
            ],
        ],
        'timezone' => 'Europe/Moscow',
    ]
];
