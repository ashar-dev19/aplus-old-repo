<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "topic_index_test_score".
 *
 * @property int $id
 * @property int $lesson_id
 * @property int $student_id
 * @property int $attempt
 * @property int $score
 * @property int $total_score
 * @property string $date_started
 * @property string $date_completed
 * @property int $status
 */
class TopicIndexTestScore extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'topic_index_test_score';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lesson_id', 'student_id', 'attempt', 'score', 'total_score', 'date_started', 'date_completed', 'status'], 'required'],
            [['lesson_id', 'student_id', 'attempt', 'score', 'total_score', 'status'], 'integer'],
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
            'score' => 'Score',
            'total_score' => 'Total Score',
            'date_started' => 'Date Started',
            'date_completed' => 'Date Completed',
            'status' => 'Status',
        ];
    }
}
