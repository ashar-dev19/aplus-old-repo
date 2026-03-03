<?php

namespace backend\modules\elearning\models;

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
 * @property int|null $createde_at
 * @property int|null $updated_at
 */
class LessonTestAttempt extends \yii\db\ActiveRecord
{
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
            [['subject_id', 'lesson_test_id', 'student_id', 'status', 'created_by', 'updated_by', 'createde_at', 'updated_at'], 'integer'],
            [['lesson_test_id', 'student_id', 'status'], 'required'],
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
            'student_id' => 'Student ID',
            'score' => 'Score',
            'status' => 'Status',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'createde_at' => 'Createde At',
            'updated_at' => 'Updated At',
        ];
    }
}
