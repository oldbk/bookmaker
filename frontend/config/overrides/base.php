<?php
/**
 * Base overrides for frontend application
 */
use \common\helpers\Convert;

return [
    // So our relative path aliases will resolve against the `/frontend` subdirectory and not nonexistent `/protected`
    'basePath' => 'frontend',
    'import' => [
        'application.controllers.*',
        'application.controllers.actions.*',
        'common.actions.*',
    ],
    'controllerMap' => [
        // Overriding the controller ID so we have prettier URLs without meddling with URL rules
        'site' => 'FrontendSiteController',
        'base' => 'FrontendBaseController',
        'error' => 'FrontendErrorController',
        'line' => 'FrontendLineController',
        'bet' => 'FrontendBetController',
        'api' => 'FrontendApiController',
        'event' => 'FrontendEventController',
        'ac' => 'FrontendAcController',
    ],
    'aliases' => [
        'commonView' => ROOT_DIR . DIRECTORY_SEPARATOR . 'frontend' . DIRECTORY_SEPARATOR . 'www' . DIRECTORY_SEPARATOR . 'themes'
            . DIRECTORY_SEPARATOR . 'main' . DIRECTORY_SEPARATOR . 'common',
        'eventView' => ROOT_DIR . DIRECTORY_SEPARATOR . 'frontend' . DIRECTORY_SEPARATOR . 'www' . DIRECTORY_SEPARATOR . 'themes'
            . DIRECTORY_SEPARATOR . 'buker' . DIRECTORY_SEPARATOR . 'events'
    ],
    'behaviors' => [
        'newRelic' => [
            'class' => 'common.extensions.yii-newrelic.behaviors.YiiNewRelicWebAppBehavior',
        ],
    ],
    'modules' => [
        'admin' => [
            'class' => 'frontend\modules\admin\AdminModule'
        ],
        'user' => [
            'class' => 'frontend\modules\user\UserModule'
        ],
        'sport' => [
            'class' => 'frontend\modules\sport\SportModule'
        ],
    ],
    'theme' => 'buker',
    'components' => [
        'errorHandler' => [
            'errorAction' => 'error/error',
        ],
        'urlManager' => [
            // Some sane usability rules
            'rules' => [
                ['admin/output/accept' . Convert::TYPE_KR, 'pattern' => '/admin/output/accept/kr/<request_id:\d+>'],
                ['admin/output/accept' . Convert::TYPE_EKR, 'pattern' => '/admin/output/accept/ekr/<request_id:\d+>'],
                ['admin/output/accept' . Convert::TYPE_GOLD, 'pattern' => '/admin/output/accept/gold/<request_id:\d+>'],
                ['admin/output/decline' . Convert::TYPE_KR, 'pattern' => '/admin/output/decline/kr/<request_id:\d+>'],
                ['admin/output/decline' . Convert::TYPE_EKR, 'pattern' => '/admin/output/decline/ekr/<request_id:\d+>'],
                ['admin/output/decline' . Convert::TYPE_GOLD, 'pattern' => '/admin/output/decline/gold/<request_id:\d+>'],

                ['admin/problem/resolve_date', 'pattern' => '/admin/problem/resolve/date/<problem_id:\d+>'],
                ['admin/problem/resolve_fora', 'pattern' => '/admin/problem/resolve/fora/<problem_id:\d+>'],
                ['admin/problem/resolve_no_result', 'pattern' => '/admin/problem/resolve/noresult/<problem_id:\d+>'],

                ['site/info', 'pattern' => '/info'],
                ['site/faq', 'pattern' => '/faq'],
                ['site/index', 'pattern' => '/', 'urlSuffix' => ''],

                ['error/error', 'pattern' => '/error'],
                ['base/login', 'pattern' => '/login'],
                ['base/logout', 'pattern' => '/logout'],

                ['admin/event/all-betting', 'pattern' => '/admin/event/all-betting'],
                ['user/finance/cancel' . Convert::TYPE_KR, 'pattern' => '/user/output/finance/kr/<balance_id:\d+>'],
                ['user/finance/cancel' . Convert::TYPE_EKR, 'pattern' => '/user/output/finance/ekr/<balance_id:\d+>'],
                ['user/finance/cancel' . Convert::TYPE_GOLD, 'pattern' => '/user/output/finance/gold/<balance_id:\d+>'],
                ['admin/output/out', 'pattern' => '/admin/output/out'],
                ['user/bet/history', 'pattern' => '/user/bet/history'],
            ]
        ],
        'log' => [
            'routes' => [
                [
                    'class' => 'vendor.malyshev.yii-debug-toolbar.YiiDebugToolbarRoute',
                    'ipFilters' => ['127.0.0.1', '192.168.1.215', '178.151.80.59'],
                ],
                [
                    'class' => 'common.extensions.loganalyzer.LALogRoute',
                    'levels' => 'info, error, warning',
                ]
            ]
        ]
    ],
];