<?php
// merge params
$params = array_merge(
    // require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);


$config = [
    'homeUrl' => Yii::getAlias('@frontendUrl'),
    'controllerNamespace' => 'frontend\controllers',
    'defaultRoute' => 'site/index',
    'bootstrap' => ['maintenance'],

     
    'as studentGuard' => [
    'class' => \frontend\components\StudentSessionGuard::class,

   
    'only' => [
        'lesson-content/*',
        'grade/*',
        'chapter/*',
        'lesson/*',
        'reports/*',
         
        // jo pages student selection ke baad hi chalne chahiye, bas wohi
    ],

    // aur jo public/seed pages hain, unhein allow:
    'except' => [
        'site/*',
        'student/current',
        'student/lesson-suggest',
        'student/lesson-info',
        'debug/*', 'gii/*',
    ],
],

    
     
    
    'modules' => [
        'store' => [
            'class' => 'app\modules\store\Module',
        ],
//        'user' => [
//            'class' => frontend\modules\user\Module::class,
//            'shouldBeActivated' => false,
//            'enableLoginByPass' => false,
//        ],
        'user-management' => [
		'class' => 'webvimark\modules\UserManagement\UserManagementModule',

		// 'enableRegistration' => true,
        //  'useEmailAsLogin' => true,

        // 'emailConfirmationRequired' => false,

		// Add regexp validation to passwords. Default pattern does not restrict user and can enter any set of characters.
		// The example below allows user to enter :
		// any set of characters
		// (?=\S{8,}): of at least length 8
		// (?=\S*[a-z]): containing at least one lowercase letter
		// (?=\S*[A-Z]): and at least one uppercase letter
		// (?=\S*[\d]): and at least one number
		// $: anchored to the end of the string

		//'passwordRegexp' => '^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$',
		

		// Here you can set your handler to change layout for any controller or action
		// Tip: you can use this event in any module
		'on beforeAction'=>function(yii\base\ActionEvent $event) {
				if ( $event->action->uniqueId == 'user-management/auth/login' )
				{
					$event->action->controller->layout = 'loginLayout.php';
				};
			},
        'auth_item_table' =>'rbac_auth_item',            
        'auth_item_group_table' =>'rbac_auth_item_group',            
        'auth_item_child_table' =>'rbac_auth_item_child',            
        'auth_assignment_table' =>'rbac_auth_assignment',
        'registrationFormClass' => 'app\models\RegistrationFormWithProfile',  
        'controllerMap' => [
        'user' => 'backend\modules\UserManagement\controllers\UserController',
        ],                        
                'viewPath' => '@backend/views/user-management',                
	],
        'file' => [
            'class' => backend\modules\file\Module::class,
        ],
    ],
    'as ghost-access'=> [
            'class' => 'webvimark\modules\UserManagement\components\GhostAccessControl',
    ],  
      
//     'as ghost-access' => [
//     'class' => 'webvimark\modules\UserManagement\components\GhostAccessControl',
//     'only' => ['create'],  // Only enable ghost access for the 'create' action
// ],
         
    'components' => [
        'authClientCollection' => [
            'class' => yii\authclient\Collection::class,
            'clients' => [
                'github' => [
                    'class' => yii\authclient\clients\GitHub::class,
                    'clientId' => env('GITHUB_CLIENT_ID'),
                    'clientSecret' => env('GITHUB_CLIENT_SECRET')
                ],
                'facebook' => [
                    'class' => yii\authclient\clients\Facebook::class,
                    'clientId' => env('FACEBOOK_CLIENT_ID'),
                    'clientSecret' => env('FACEBOOK_CLIENT_SECRET'),
                    'scope' => 'email,public_profile',
                    'attributeNames' => [
                        'name',
                        'email',
                        'first_name',
                        'last_name',
                    ]
                ]
            ]
        ],

      


         
// 'mailer' => [
//     'class' => yii\swiftmailer\Mailer::class,
//     'viewPath' => '@frontend/mail',
//     'useFileTransport' => false,
//     'transport' => [
//         'class'      => Swift_SmtpTransport::class,
//         'host'       => 'smtp.gmail.com',
//         'username'   => Yii::$app->params['smtpUsername'],
//         'password'   => Yii::$app->params['smtpPassword'],
//         'port'       => 465,    
//         'encryption' => 'ssl', 
//        
//         'streamOptions' => [
//             'ssl' => [
//                 'verify_peer'       => false,
//                 'verify_peer_name'  => false,
//                 'allow_self_signed' => true,
//             ],
//             'socket' => [
//                 'connect_timeout' => 30,  
//             ],
//         ],
//     ],
// ],

// 'mailer' => [
//         'class' => 'yii\swiftmailer\Mailer',
//         'viewPath' => '@common/mail',
//         'useFileTransport' => false,
//         'transport' => [
//             'class'      => 'Swift_SmtpTransport',
//             'host'       => 'aplustudents.com',
//             'username'   => 'reports@aplustudents.com',
//             'password'   => '+W&?KhbU&U*}',
//             // 'port'       => 587,
//             // 'encryption' => 'tls',
//             // if that fails, try port 465 + ssl
//             'port'       => 465,
//             'encryption' => 'ssl',
//         ],
//     ],

    
 'mailer' => [
    'class' => 'yii\swiftmailer\Mailer',
    'useFileTransport' => true,
],





        'errorHandler' => [
            'errorAction' => 'site/error'
        ],
        'maintenance' => [
            'class' => common\components\maintenance\Maintenance::class,
            'enabled' => function ($app) {
                if (env('APP_MAINTENANCE') === '1') {
                    return true;
                }
                return $app->keyStorage->get('frontend.maintenance') === 'enabled';
            }
        ],
        'request' => [
            'cookieValidationKey' => env('FRONTEND_COOKIE_VALIDATION_KEY')
        ],
//        'user' => [
//            'class' => yii\web\User::class,
//            'identityClass' => common\models\User::class,
//            'loginUrl' => ['/user/sign-in/login'],
//            'enableAutoLogin' => true,
//            'as afterLogin' => common\behaviors\LoginTimestampBehavior::class
//        ]
//         'user' => [
// //		'class' => 'webvimark\modules\UserManagement\components\UserConfig',
//                 'class' => 'backend\components\UserConfig',    
// 		// Comment this if you don't want to record user logins
// 		'on afterLogin' => function($event) {
// 				\webvimark\modules\UserManagement\models\UserVisitLog::newVisitor($event->identity->id);
// 			}
// 	    ],

'user' => [
    // you’re already using your custom config, keep it
    'class' => 'backend\components\UserConfig',

    // Auto-assign default role on every successful login
    'on afterLogin' => function ($event) {
        // keep existing visitor logging
        \webvimark\modules\UserManagement\models\UserVisitLog::newVisitor($event->identity->id);

        // assign "user" role if not already assigned
        try {
            $id = $event->identity->id ?? null;
            if ($id && !\webvimark\modules\UserManagement\models\rbacDB\AuthAssignment::find()
                    ->where(['user_id' => $id, 'item_name' => 'user'])
                    ->exists()) {
                \webvimark\modules\UserManagement\models\User::assignRole($id, 'user');
            }
        } catch (\Throwable $e) {
            Yii::error('Auto-assign user role failed: ' . $e->getMessage(), __METHOD__);
        }
    },

    // NEW: afterLogout redirect to main site
    'on afterLogout' => function ($event) {
        Yii::$app->getResponse()->redirect('https://aplustudents.com/')->send();
        Yii::$app->end();
    },


],

        
        
        'formatter' => [
            'dateFormat' => 'dd.MM.yyyy']        
    ]
];

// if (YII_ENV_DEV) {
//     $config['modules']['gii'] = [
//         'class' => yii\gii\Module::class,
//         'generators' => [
//             'crud' => [
//                 'class' => yii\gii\generators\crud\Generator::class,
//                 'messageCategory' => 'frontend'
//             ]
//         ]
//     ];
// }

if (YII_ENV_DEV) {
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'generators' => [
            'crud' => [
                'class' => 'yii\gii\generators\crud\Generator',
                'messageCategory' => 'frontend'
            ],
        ],
    ];
}



return $config;
