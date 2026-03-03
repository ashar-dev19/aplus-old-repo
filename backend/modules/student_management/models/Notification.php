<?php

namespace backend\modules\student_management\models;

use backend\modules\UserManagement\models\UserProfile;
use User;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;


/**
 * This is the model class for table "notification".
 *
 * @property int $id
 * @property int $client_id
 * @property int|null $parent_id
 * @property string|null $parent_type
 * @property string|null $type
 * @property string|null $medium
 * @property string|null $to_number
 * @property string|null $from_text
 * @property string $title
 * @property string $contents
 * @property int $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class Notification extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notification';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['client_id', 'title', 'contents', 'status'], 'required'],
            [['client_id', 'parent_id', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['contents'], 'string'],
            [['parent_type', 'type', 'medium'], 'string', 'max' => 25],
            [['to_number'], 'string', 'max' => 15],
            [['from_text', 'title'], 'string', 'max' => 35],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            BlameableBehavior::class,
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'client_id' => 'Client ID',
            'parent_id' => 'Parent ID',
            'parent_type' => 'Parent Type',
            'type' => 'Type',
            'medium' => 'Medium',
            'to_number' => 'To Number',
            'from_text' => 'From Text',
            'title' => 'Title',
            'contents' => 'Contents',
            'status' => 'Status',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }


    public function beforeSave($insert)
{
    if ($insert) {
        $this->id = null; 
    }
    return parent::beforeSave($insert);
}


// Getter methods getting attributes 
/**
     * Gets query for [[student name]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStudent() 
    {
      return $this->hasOne(Student::className(), ['id' => 'client_id']);
    }

    public function getParent() 
    {
      return $this->hasOne(UserProfile::className(), ['user_id' => 'parent_id']);
    }

}
