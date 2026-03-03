<?php

namespace backend\modules\student_management\models;
use backend\modules\UserManagement\models\UserProfile;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;


/**
 * This is the model class for table "points".
 *
 * @property int $id
 * @property float $points
 * @property int $student_id
 * @property string|null $details
 * @property int|null $is_redempt
 * @property string|null $reason
 * @property string|null $redemption_date
 * @property int $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $update_at
 *
 * @property Student $student
 */
class Points extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'points';
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

    public function rules()
    {
        return [
            [['points', 'student_id', 'status'], 'required'],
            [['points'], 'number'],
            [['student_id', 'is_redempt', 'status', 'created_by', 'updated_by', 'created_at', 'update_at'], 'integer'],
            [['details', 'reason'], 'string'],
            [['redemption_date'], 'safe'],
            [['student_id'], 'exist', 'skipOnError' => true, 'targetClass' => Student::className(), 'targetAttribute' => ['student_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'points' => 'Points',
            'student_id' => 'Student ID',
            'details' => 'Details',
            'is_redempt' => 'Is Redempt',
            'reason' => 'Reason',
            'redemption_date' => 'Redemption Date',
            'status' => 'Status',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'update_at' => 'Update At',
        ];
    }

    /**
     * Gets query for [[Student]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStudent()
    {
        return $this->hasOne(Student::className(), ['id' => 'student_id']);
    }
    // public function getParent() 
    // {
    //   return $this->hasOne(UserProfile::className(), ['user_id' => 'student_id']);
    // }
}
