<?php

namespace backend\modules\UserManagement\models;

use webvimark\helpers\LittleBigHelper;
use webvimark\helpers\Singleton;
use webvimark\modules\UserManagement\components\AuthHelper;
use webvimark\modules\UserManagement\components\UserIdentity;
use webvimark\modules\UserManagement\models\rbacDB\Role;
use webvimark\modules\UserManagement\models\rbacDB\Route;
use webvimark\modules\UserManagement\UserManagementModule;
use webvimark\modules\UserManagement\models\User;

use backend\modules\studentms\models\Student;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $email
 * @property integer $email_confirmed
 * @property string $auth_key
 * @property string $password_hash
 * @property string $confirmation_token
 * @property string $bind_to_ip
 * @property string $registration_ip
 * @property integer $status
 * @property integer $superadmin
 * @property integer $created_at
 * @property integer $updated_at
 */
class ParentUser
    extends \webvimark\modules\UserManagement\models\User
{
	public $user_student_id;
	
	public function beforeSave($insert) {
        
        if (parent::beforeSave($insert)) {
            // if(!isset($this->company_id))
            //     $this->company_id = Yii::$app->user->company->id;
        }
        return parent::beforeSave($insert);
        
    }
	public function afterSave($insert, $changedAttributes) {
        
        	
			// if($_POST['Saleuser']['user_student_id']!="") 
			// {	$student = Student::findOne($_POST['Saleuser']['user_student_id']);
			
			// 	// if($_POST['Saleuser']['user_student_id'] and $student instanceof Student){
			// 	// 		print "I a, here in af";
			// 	// 		print "uid:".$student->user_id = $this->id;
			// 	// 		$student->save();
			// 	// }
			// }
        parent::afterSave($insert, $changedAttributes);
        
    }

    public function getUserProfile()
    {
        return $this->hasOne(UserProfile::className(), ['user_id' => 'id']);
    }
	
}