<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "lesson_test".
 *
 * @property int $id
 * @property int $lesson_id
 * @property int $student_id
 * @property int $attempt
 * @property int $points
 * @property int $total_points
 * @property string $date_started
 * @property string $date_completed
 * @property int $status
 */
class LessonTest extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lesson_test';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lesson_id', 'student_id', 'attempt', 'points', 'total_points', 'date_started', 'date_completed', 'status'], 'required'],
            [['lesson_id', 'student_id', 'attempt', 'points', 'total_points', 'status'], 'integer'],
            [['date_started', 'date_completed'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'lesson_id' => 'Lesson ID',
            'student_id' => 'Student ID',
            'attempt' => 'Attempt',
            'points' => 'Points',
            'total_points' => 'Total Points',
            'date_started' => 'Date Started',
            'date_completed' => 'Date Completed',
            'status' => 'Status',
        ];
    }
}
