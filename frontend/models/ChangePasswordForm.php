<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use webvimark\modules\UserManagement\models\User;

class ChangePasswordForm extends Model
{
    public $oldPassword;
    public $newPassword;
    public $repeatPassword;

    public function rules()
    {
        return [
            [['oldPassword', 'newPassword', 'repeatPassword'], 'required'],
            ['newPassword', 'string', 'min' => 6],
            ['repeatPassword', 'compare', 'compareAttribute' => 'newPassword', 'message' => "Passwords don't match"],
        ];
    }

    public function changePassword()
    {
        $user = User::findOne(Yii::$app->user->id);

        if (!$user || !Yii::$app->security->validatePassword($this->oldPassword, $user->password_hash)) {
            $this->addError('oldPassword', 'Old password is incorrect');
            return false;
        }

        $user->scenario = 'changePassword';
        $user->password = $this->newPassword;

        return $user->save(false);
    }
}
