<?php

namespace backend\modules\elearning\models;

use Yii;

/**
 * This is the model class for table "lesson_test_options".
 *
 * @property int $id
 * @property int $question_id
 * @property int $subject_id
 * @property int $test_id
 * @property int $lesson_id
 * @property string $title
 * @property int $is_clue
 * @property int $is_answer
 * @property string $content
 * @property int|null $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class LessonTestOptions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lesson_test_options';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['question_id', 'subject_id', 'test_id', 'lesson_id', 'title', 'is_clue', 'is_answer', 'content'], 'required'],
            [['question_id', 'subject_id', 'test_id', 'lesson_id', 'is_clue', 'is_answer', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['content'], 'string'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'question_id' => 'Question ID',
            'subject_id' => 'Subject ID',
            'test_id' => 'Test ID',
            'lesson_id' => 'Lesson ID',
            'title' => 'Title',
            'is_clue' => 'Is Clue',
            'is_answer' => 'Is Answer',
            'content' => 'Content',
            'status' => 'Status',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
