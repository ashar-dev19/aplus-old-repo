<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "userimported".
 *
 * @property int $id Primary Key
 * @property string $email Email
 * @property string $password
 * @property string $fname
 * @property string $lname
 * @property string $phone_number
 * @property string $address
 * @property string $date_created
 * @property int $is_active
 * @property string $last_login
 * @property int $date_updated
 * @property int $is_deleted
 * @property int $is_verified
 * @property string $verification_salt
 * @property int $is_admin
 */
class Userimported extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'userimported';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'email', 'password', 'fname', 'lname', 'phone_number', 'address', 'date_created', 'is_active', 'last_login', 'date_updated', 'is_deleted', 'is_verified', 'verification_salt'], 'required'],
            [['id', 'is_active', 'date_updated', 'is_deleted', 'is_verified', 'is_admin'], 'integer'],
            [['address'], 'string'],
            [['date_created', 'last_login'], 'safe'],
            [['email', 'password', 'fname', 'lname', 'verification_salt'], 'string', 'max' => 255],
            [['phone_number'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'password' => 'Password',
            'fname' => 'Fname',
            'lname' => 'Lname',
            'phone_number' => 'Phone Number',
            'address' => 'Address',
            'date_created' => 'Date Created',
            'is_active' => 'Is Active',
            'last_login' => 'Last Login',
            'date_updated' => 'Date Updated',
            'is_deleted' => 'Is Deleted',
            'is_verified' => 'Is Verified',
            'verification_salt' => 'Verification Salt',
            'is_admin' => 'Is Admin',
        ];
    }
}
