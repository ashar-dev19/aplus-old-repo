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
 * @property int $question_id
 * @property int $student_id
 * @property int $attempt
 * @property float|null $score
 * @property int $total_score
 * @property int $points_earned
 * @property int $status
 * @property int $time_spent
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
            [['subject_id', 'lesson_test_id', 'question_id', 'student_id', 'attempt', 'total_score', 'points_earned', 'status', 'time_spent', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['lesson_test_id', 'question_id', 'student_id', 'attempt', 'total_score', 'points_earned', 'status', 'time_spent'], 'required'],
            [['score'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'subject_id' => 'Subject ID',
            'lesson_test_id' => 'Lesson Test ID',
            'question_id' => 'Question ID',
            'student_id' => 'Student ID',
            'attempt' => 'Attempt',
            'score' => 'Score',
            'total_score' => 'Total Score',
            'points_earned' => 'Points Earned',
            'status' => 'Status',
            'time_spent' => 'Time Spent',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
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
