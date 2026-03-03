<?php

namespace backend\modules\student_management\models;
use webvimark\modules\UserManagement\models\User;
use backend\modules\UserManagement\models\ParentUser;
use backend\modules\elearning\models\LessonRead;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "student".
 *
 * @property int $id
 * @property int $parent_id
 * @property string $full_name
 * @property string|null $email
 * @property string|null $details
 * @property int|null $grade_id
 * @property int|null $live_support
 * @property int $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $updated_at
 * @property int|null $created_at
 *
 * @property Grade $grade
 * @property LessonRead[] $lessonReads
 * @property Points[] $points
 */
class Student extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'student';
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
            [['parent_id', 'full_name', 'status'], 'required'],
            [['parent_id', 'grade_id', 'live_support', 'status', 'created_by', 'updated_by', 'updated_at', 'created_at'], 'integer'],
            [['details'], 'string'],
            [['full_name', 'email'], 'string', 'max' => 50],
            [['grade_id'], 'exist', 'skipOnError' => true, 'targetClass' => Grade::className(), 'targetAttribute' => ['grade_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'Parent ID',
            'full_name' => 'Full Name',
            'email' => 'Email',
            'details' => 'Details',
            'grade_id' => 'Grade ID',
            'live_support' => 'Live Support',
            'status' => 'Status',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Grade]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGrade()
    {
        return $this->hasOne(Grade::className(), ['id' => 'grade_id']);
    }

    /**
     * Gets query for [[LessonReads]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLessonReads()
    {
        return $this->hasMany(LessonRead::className(), ['student_id' => 'id']);
    }

    /**
     * Gets query for [[Points]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPoints()
    {
        return $this->hasMany(Points::className(), ['student_id' => 'id']);
    }

    public function getParent()
    {
        return $this->hasOne(ParentUser::className(), ['id' => 'parent_id']);
    }

    public function getStudentPoints()
    {
        $studentPoints = 0;
        // foreach ($this->points as $point) {
        //     $studentPoints+= $point->points;
        // }
        return Points::find()->where(['student_id' => $this->id])->sum('points'); 
        
    }


     // Add a method to get the static box item
    



}
