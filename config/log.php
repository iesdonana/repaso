<?php

if (YII_ENV === 'prod') {
    return [
        'flushInterval' => 1,
        'traceLevel' => YII_DEBUG ? 3 : 0,
        'targets' => [
            [
                'class' => 'jones\herokulogger\HerokuTarget',
                'levels' => ['error', 'warning', 'info'],
                'exportInterval' => 1,
                'logVars' => []
            ]
        ]
    ];
}

return [
    'flushInterval' => 1,
    'traceLevel' => YII_DEBUG ? 3 : 0,
    'targets' => [
        [
            'class' => 'yii\log\FileTarget',
            'levels' => ['error', 'warning'],
        ]
    ]
];
