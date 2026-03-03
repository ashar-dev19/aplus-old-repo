<?php

namespace backend\modules\UserManagement\models;

use trntv\filekit\behaviors\UploadBehavior;
use \backend\modules\hospital\models\Services;
use backend\modules\hospital\models\Department;
use \backend\modules\hospital\models\Process;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user_profile".
 *
 * @property integer $user_id
 * @property integer $locale
 * @property string  $firstname
 * @property string  $middlename
 * @property string  $lastname
 * @property string  $surname
 * @property string  $family_name
 * @property string  $parent_firstname
 * @property string  $parent_lastname
 * @property string  $unit_number
 * @property string  $city
 * @property string  $province
 * @property string  $postal_code
 * @property string  $country
 * @property string  $address
 * @property string  $qualification
 * @property string  $profession
 * @property string  $job_title
 * @property string  $join_date
 * @property string  $last_date
 * @property string  $email_alternate
 * @property string  $alt_email1
 * @property string  $alt_email2
 * @property string  $phone
 * @property string  $phone_alt
 * @property string  $emergency_contact
 * @property string  $avatar_path
 * @property string  $avatar_base_url
 * @property integer $gender
 *
 * @property User    $user
 */
class UserProfile extends ActiveRecord
{
    
    

    /**
     * @var yii\web\UploadedFile
     */
    public $picture;

    const GENDER_MALE   = 1;
    const GENDER_FEMALE = 2;

    public static function tableName()
    {
        return '{{%user_profile}}';
    }

    public function behaviors()
    {
        return [
            'picture' => [
                'class'          => UploadBehavior::className(),
                'attribute'      => 'picture',
                'pathAttribute'  => 'avatar_path',
                'baseUrlAttribute' => 'avatar_base_url',
            ],
        ];
    }

    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'gender'], 'integer'],

            // core profile fields
            [['firstname','middlename','lastname','surname','family_name','parent_firstname','parent_lastname'], 'string', 'max' => 64],
            [['unit_number'],     'string', 'max' => 64],
            [['city'],            'string', 'max' => 128],
            [['province','country'], 'string', 'max' => 64],
            [['postal_code'],     'string', 'max' => 16],
            [['address','qualification','profession','job_title'], 'string', 'max' => 255],
            [['join_date','last_date','date_of_birth'], 'safe'],

            // alternate contacts
            [['email_alternate'], 'email'],
            
            [['phone','phone_alt','emergency_contact','CNIC','passport_number'], 'string', 'max' => 32],

            // other existing fields
            ['locale', 'default', 'value' => Yii::$app->language],
            ['locale', 'in', 'range' => array_keys(Yii::$app->params['availableLocales'])],
            [['picture'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'user_id'           => Yii::t('common','User ID'),
            'firstname'         => Yii::t('common','First Name'),
            'middlename'        => Yii::t('common','Middle Name'),
            'lastname'          => Yii::t('common','Last Name'),
            'surname'           => Yii::t('common','Surname'),
            'family_name'       => Yii::t('common','Family Name'),
            'parent_firstname'  => 'Parent First Name',
            'parent_lastname'   => 'Parent Last Name',
            'unit_number'       => 'Unit / Apt #',
            'city'              => 'City',
            'province'          => 'Province / State',
            'postal_code'       => 'Postal Code',
            'country'           => 'Country',
            'address'           => 'Address',
            'qualification'     => 'Qualification',
            'profession'        => 'Profession',
            'job_title'         => 'Job Title',
            'join_date'         => 'Join Date',
            'last_date'         => 'Last Date',
            'date_of_birth'     => 'Date of Birth',
            'alt_email1'        => 'Alternative Email #1',
            'alt_email2'        => 'Alternative Email #2',
            'email_alternate'   => 'Alternate Email',
            'phone'             => 'Phone',
            'phone_alt'         => 'Alternative Phone',
            'emergency_contact' => 'Emergency Contact',
            'CNIC'              => 'CNIC',
            'passport_number'   => 'Passport Number',
            'avatar_path'       => 'Avatar Path',
            'avatar_base_url'   => 'Avatar Base URL',
            'gender'            => Yii::t('common','Gender'),
            'locale'            => Yii::t('common','Locale'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\webvimark\modules\UserManagement\models\User::class, ['id'=>'user_id']);
    }

    /**
     * @return string|null
     */
    public function getFullName()
    {
        $parts = array_filter([
            $this->firstname,
            $this->middlename,
            $this->lastname
        ]);
        return $parts ? implode(' ', $parts) : null;
    }

    /**
     * @return string
     */
    public function getAvatar($default = null)
    {
        return $this->avatar_path
            ? Yii::getAlias($this->avatar_base_url . '/' . $this->avatar_path)
            : $default;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        $user = $this->getUser()->one();
        return $user && $user->company_id
            ? \backend\modules\studentms\models\Institute::findOne($user->company_id)
            : null;
    }
}