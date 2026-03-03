<?php

namespace backend\modules\elearning\models;

use Yii;

/**
 * This is the model class for table "lesson_test_question".
 *
 * @property int $id
 * @property int $subject_id
 * @property int $lesson_test_id
 * @property string $title
 * @property string|null $content
 * @property int $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class LessonTestQuestion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lesson_test_question';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['subject_id', 'lesson_test_id', 'title', 'status'], 'required'],
            [['subject_id', 'lesson_test_id', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['content'], 'string'],
            [['title'], 'string', 'max' => 50],
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
            'title' => 'Title',
            'content' => 'Content',
            'status' => 'Status',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
