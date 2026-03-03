<?php
return [
    
    'modules'=>[
	'user-management' => [
		'class' => 'webvimark\modules\UserManagement\UserManagementModule',
	        'controllerNamespace'=>'vendor\webvimark\modules\UserManagement\controllers', // To prevent yii help from crashing
	],
    ],
];
