<?php

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'banners' => [
            'class' => 'backend\modules\banners\Module',
        ],
        'common' => [
            'class' => 'backend\modules\common\Module',
        ],
        'roles' => [
            'class' => 'backend\modules\roles\Module',
        ],
        'users' => [
            'class' => 'backend\modules\users\Module',
        ],
		'article' => [
            'class' => 'backend\modules\article\Module',
        ],
		'packages' => [
            'class' => 'backend\modules\packages\Module',
        ],
		'notifications' => [
            'class' => 'backend\modules\notifications\Module',
        ],
		'plans' => [
            'class' => 'backend\modules\plans\Module',
        ],
		'webinar' => [
            'class' => 'backend\modules\webinar\Module',
        ],
		'glucose' => [
            'class' => 'backend\modules\glucose\Module',
        ],
		 'clinics' => [
            'class' => 'backend\modules\clinics\Module',
        ],
		'callcentre' => [
            'class' => 'backend\modules\callcentre\Module',
        ],
		 'franchies' => [
            'class' => 'backend\modules\franchies\Module',
        ],

    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
    		'view' => [
    				'theme' => [
    						'pathMap' => [
    								'@app/views' => '@vendor/dmstr/yii2-adminlte-asset/example-views/yiisoft/yii2-app'
    						],
    				],
    		],
			
    ],
    'params' => $params,
];
