<?php

$config = [

    'homeUrl' => Yii::getAlias('@backendUrl'),

    'controllerNamespace' => 'backend\controllers',

    'defaultRoute' => 'timeline-event/index',

       

    'on beforeRequest' => function () {
        // throttle: max 2 minute me 1 dafa
        $cache = \Yii::$app->cache ?? null;
        $key   = 'contract_expiry_last_run_2m';   // <- rename (optional)

        if ($cache && $cache->get($key)) {
            return; // recently ran
        }

        \webvimark\modules\UserManagement\models\User::expireDueContracts();

        if ($cache) {
            $cache->set($key, 1, 120); // 120s = 2 min
        }
    },

    

    

    'components' => [

        'errorHandler' => [

            'errorAction' => 'site/error',

        ],

        'notifier' => ['class' => \backend\components\Notifier::class],

        'request' => [

            'cookieValidationKey' => env('BACKEND_COOKIE_VALIDATION_KEY'),

            'baseUrl' => env('BACKEND_BASE_URL'),

        ],

//        'user' => [

//            'class' => yii\web\User::class,

//            'identityClass' => common\models\User::class,

//            'loginUrl' => ['sign-in/login'],

//            'enableAutoLogin' => true,

//            'as afterLogin' => common\behaviors\LoginTimestampBehavior::class,

//        ],

        'user' => [

//		'class' => 'webvimark\modules\UserManagement\components\UserConfig',

                'class' => 'backend\components\UserConfig',    

		// Comment this if you don't want to record user logins

		'on afterLogin' => function($event) {

				\webvimark\modules\UserManagement\models\UserVisitLog::newVisitor($event->identity->id);

			}

	],
    

        'formatter' => [

            'dateFormat' => 'dd.MM.yyyy']      

    ],
    

    'modules' => [

        'store' => [

            'class' => 'app\modules\store\Module',

        ],

        'elearning' => [

            'class' => 'backend\modules\elearning\Module',

        ],

        'student-management' => [

            'class' => backend\modules\student_management\Module::class,

        ],

        'content' => [

            'class' => backend\modules\content\Module::class,

        ],

        'widget' => [

            'class' => backend\modules\widget\Module::class,

        ],

        'file' => [

            'class' => backend\modules\file\Module::class,

        ],

        'system' => [

            'class' => backend\modules\system\Module::class,

        ],

        'translation' => [

            'class' => backend\modules\translation\Module::class,

        ],

//        'rbac' => [

//            'class' => backend\modules\rbac\Module::class,

//            'defaultRoute' => 'rbac-auth-item/index',

//        ],

        'user-management' => [

		'class' => 'webvimark\modules\UserManagement\UserManagementModule',



		// 'enableRegistration' => true,



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

                'viewPath' => '@app/views/user-management',                

	    ],                       

    ],

//    'as globalAccess' => [

//        'class' => common\behaviors\GlobalAccessBehavior::class,

//        'rules' => [

//            [

//                'controllers' => ['sign-in'],

//                'allow' => true,

//                'roles' => ['?'],

//                'actions' => ['login'],

//            ],

//            [

//                'controllers' => ['sign-in'],

//                'allow' => true,

//                'roles' => ['@'],

//                'actions' => ['logout'],

//            ],

//            [

//                'controllers' => ['site'],

//                'allow' => true,

//                'roles' => ['?', '@'],

//                'actions' => ['error'],

//            ],

//            [

//                'controllers' => ['debug/default'],

//                'allow' => true,

//                'roles' => ['?'],

//            ],

//            [

//                'controllers' => ['user'],

//                'allow' => true,

//                'roles' => ['administrator'],

//            ],

//            [

//                'controllers' => ['user'],

//                'allow' => false,

//            ],

//            [

//                'allow' => true,

//                'roles' => ['manager', 'administrator'],

//            ],

//        ],

//    ],

      'as ghost-access'=> [

            'class' => 'webvimark\modules\UserManagement\components\GhostAccessControl',

            ],  

                                     

];



if (YII_ENV_DEV) {

    $config['modules']['gii'] = [

        'class' => yii\gii\Module::class,

        'generators' => [

            'crud' => [

                'class' => yii\gii\generators\crud\Generator::class,

                'templates' => [

                    'yii2-starter-kit' => Yii::getAlias('@backend/views/_gii/templates'),

                ],

                'template' => 'yii2-starter-kit',

                'messageCategory' => 'backend',

            ],

        ],

    ];

}



return $config;

