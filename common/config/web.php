<?php

$config = [

  
    // 'timeZone' => env('APP_TIMEZONE', 'America/Toronto'),
    'timeZone' => env('APP_TIMEZONE', 'America/Edmonton'),

    'components' => [
        'assetManager' => [
            'class' => yii\web\AssetManager::class,
            'linkAssets' => env('LINK_ASSETS'),
            'appendTimestamp' => YII_ENV_DEV
        ],
        'formatter' => [
            'class' => '\common\components\CustomFormatter',
          
           // Store/internal UTC, display in app TZ:
        //   'defaultTimeZone' => 'UTC',
        //    'timeZone'        => env('APP_TIMEZONE', 'America/Toronto'),

           'defaultTimeZone' => 'UTC', 
            'timeZone'        => env('APP_TIMEZONE', 'America/Edmonton'), 
            'dateFormat'      => 'php:Y-m-d',
            'datetimeFormat'  => 'php:Y-m-d H:i:s',
            'timeFormat'      => 'php:H:i',
        ],

		// 'mailer' => [
		// 	'class' => 'yii\swiftmailer\Mailer',
		// 	'transport' => [
		// 		'class' => 'Swift_SmtpTransport',
		// 		'host' => 'smtp.gmail.com',
		// 		'username' => 'No-reply@mihe.ac.uk',	   
		// 		'password' => 'emdavlfkjzfbvdwz',
		// 		'port' => '465',
		// 		'encryption' => 'ssl',
		// 		],
		// ],  
        // 'mailer' => [
        //     'class' => 'yii\swiftmailer\Mailer',
        //     'viewPath' => '@common/mail',
        //     'useFileTransport' => false,
        //     'transport' => [
        //         'class' => 'Swift_SmtpTransport',
        //         'host' => 'aplustudents.com',
        //         'username' => 'reports@aplustudents.com',
        //         'password' => '+W&?KhbU&U*}',
        //         // 'port' => 587,
        //         'port' => 2083,
        //         'encryption' => 'tls',
        //         // 'encryption' => 'ssl',
        //     ],
        // ], 
        

        	// 'mailer_student_portal' => [
		// 	'class' => 'yii\swiftmailer\Mailer',
		// 	'transport' => [
		// 		'class' => 'Swift_SmtpTransport',
		// 		'host' => 'smtp.gmail.com',
		// 		'username' => 'Student-portal@mihe.ac.uk',	   
		// 		'password' => 'ndushruamgwknmud',
		// 		'port' => '465',
		// 		'encryption' => 'ssl',
		// 		],

		// ],   	

        
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => true,
        ],



        
       'user' => [
            'identityClass' => 'webvimark\modules\UserManagement\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['user-management/auth/login'], // Your custom login URL
            'on afterLogin' => function($event) {
                Yii::$app->response->redirect(['student/current'])->send();
                Yii::$app->end();
            },
        ],

        // 'user' => [
        //      'identityClass' => 'webvimark\modules\UserManagement\models\User',
        //     'enableAutoLogin' => true,
        //     'loginUrl' => ['user-management/auth/login'],

        //     // run on every successful login
        //     'on afterLogin' => function ($event) {
        //         $u = \Yii::$app->user->identity;

        //         if ($u instanceof \webvimark\modules\UserManagement\models\User) {
                    
        //             if ($u->isContractExpired()) {
        //                 // mark inactive and logout
        //                 $u->updateAttributes([
        //                     'status' => \webvimark\modules\UserManagement\models\User::STATUS_INACTIVE,
        //                 ]);
        //                 \Yii::$app->user->logout(false);
        //                 \Yii::$app->session->setFlash('error', 'Your contract has expired.');

        //                 // expired → login page
        //                 \Yii::$app->response->redirect(['user-management/auth/login'])->send();
        //                 \Yii::$app->end();
        //                 return;
        //             }
        //         }

                 
        //         \Yii::$app->response->redirect(['student/current'])->send();
        //         \Yii::$app->end();
        //     },
        // ],



	


	],

    'as locale' => [

        'class' => common\behaviors\LocaleBehavior::class,

        'enablePreferredLanguage' => true

    ],

    'container' => [

        'definitions' => [

           \yii\widgets\LinkPager::class => \yii\bootstrap4\LinkPager::class,

        ],

    ],

];



if (YII_DEBUG) {

    $config['bootstrap'][] = 'debug';

    $config['modules']['debug'] = [

        'class' => yii\debug\Module::class,

        'allowedIPs' => ['*'],

    ];

}



if (YII_ENV_DEV) {

    $config['modules']['gii'] = [

        'allowedIPs' => ['*'],

    ];

}





return $config;

