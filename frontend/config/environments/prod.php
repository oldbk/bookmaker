<?php
/**
 * Specific config overrides for frontend entry point at production server.
 */
return [
    'components' => [
        'errorHandler' => [
            'errorAction' => 'error/error',
        ],
    ],
    'params' => [
        // Provide your real GA ID so the relevant code will automatically be inserted in layout.
        'google.analytics.id' => 'XXXX-XXXX-XXXX'
    ]
];

