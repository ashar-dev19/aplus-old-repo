<?php

namespace frontend\models;

use yii\behaviors\BlameableBehavior;

use yii\behaviors\SluggableBehavior;

use yii\behaviors\TimestampBehavior;

use Yii;

/**
 * This is the model class for table "lesson_test_attempt".
 *
 * @property int $id
 * @property int|null $subject_id
 * @property int $lesson_test_id
 * @property int $student_id
 * @property float|null $score
 * @property int $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class LessonTestAttempt extends \yii\db\ActiveRecord
{
    // Define constants for attempt status
    const STATUS_INCOMPLETE = 0;
    const STATUS_COMPLETE = 1;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lesson_test_attempt';
    }
    

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['subject_id', 'lesson_test_id', 'question_id', 'student_id', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['lesson_test_id', 'student_id', 'status'], 'required'],
            [['score'], 'number'],
        ];
    }

    // public function behaviors()

    // {

    //     return [

    //         TimestampBehavior::class,
    //         BlameableBehavior::class,

    //     ];
    // }   

    

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'subject_id' => 'Subject ID',
            'lesson_test_id' => 'Lesson Test ID',
            'student_id' => 'Student ID',
            'score' => 'Score',
            'status' => 'Status',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Createde At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Lesson]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLesson()
    {
        return $this->hasOne(Lesson::className(), ['id' => 'lesson_test_id']);
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

    
    
}
