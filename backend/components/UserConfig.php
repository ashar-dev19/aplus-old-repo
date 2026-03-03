<?php

namespace backend\components;

use yii\web\User;
use Yii;

//use backend\modules\UserManagement\models\UserProfile;
//use common\models\UserProfile;
use backend\modules\UserManagement\models\UserProfile;
use webvimark\modules\UserManagement\models\rbacDB\Role;


/**
 * Class UserConfig
 * @package webvimark\modules\UserManagement\components
 */
class UserConfig extends User
{
    /**
	 * @inheritdoc
	 */
	public $identityClass = 'webvimark\modules\UserManagement\models\User';

	/**
	 * @inheritdoc
	 */
	public $enableAutoLogin = true;
	
	/**
 	 * @inheritdoc
	 */
	public $cookieLifetime = 2592000;
  
	/**
	 * @inheritdoc
	 */
	public $loginUrl = ['/user-management/auth/login'];

	/**
	 * Allows to call Yii::$app->user->isSuperadmin
	 *
	 * @return bool
	 */
	public function getIsSuperadmin()
	{
		return @Yii::$app->user->identity->superadmin == 1;
	}

	/**
	 * @return string
	 */
	public function getUsername()
	{
		return @Yii::$app->user->identity->username;
	}
        
        /**
	 * @return string
	 */
	public function getRole()
	{
		$userRoles = Role::getAvailableRoles();
		return count($userRoles) > 0 ? $userRoles[0]->name : '';
	}
	/**
	 * @return profile object
	 */
	public function getUserProfile()
	{
		$userProfile = UserProfile::findOne(['user_id'=>  $this->id]);
		return $userProfile;
	}
        
	/**
	 * @return profile object
	 */
	public function getProfile()
	{
		$userProfile = UserProfile::findOne(['user_id'=>  $this->id]);
		return $userProfile;
	}

        


	/**
	 * @inheritdoc
	 */
	protected function afterLogin($identity, $cookieBased, $duration)
	{
            \webvimark\modules\UserManagement\components\AuthHelper::updatePermissions($identity);

		parent::afterLogin($identity, $cookieBased, $duration);
	}
        
        // public function getCompany() {
        // return
        //         Institute::findOne(
        // \webvimark\modules\UserManagement\models\User::findOne(Yii::$app->user->id)->company_id);
        // }
        
        public function getStudent() {
        return
        \backend\modules\studentms\models\Student::findOne(['user_id' => Yii::$app->user->id]);
        }

}
