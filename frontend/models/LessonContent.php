<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "lesson_content".
 *
 * @property int $id
 * @property int $subject_id
 * @property int $chapter_id
 * @property int $lesson_id
 * @property int $question_id
 * @property string $title
 * @property string $content
 * @property string|null $options
 * @property int $points
 * @property int $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class LessonContent extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lesson_content';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['subject_id', 'chapter_id', 'lesson_id', 'question_id', 'title', 'content', 'points', 'status'], 'required'],
            [['subject_id', 'chapter_id', 'lesson_id', 'question_id', 'points', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['title', 'content', 'options'], 'string'],
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
            'chapter_id' => 'Chapter ID',
            'lesson_id' => 'Lesson ID',
            'question_id' => 'Question ID',
            'title' => 'Title',
            'content' => 'Content',
            'options' => 'Options',
            'points' => 'Points',
            'status' => 'Status',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getTestQuestionAnswers()
    {
        return $this->hasMany(TestQuestionAnswer::className(), ['question_id' => 'question_id']);
    }
    
        public function getSubject()
    {
        return $this->hasOne(Subject::class, ['id' => 'subject_id']);
    }

    public function getExplanations()
    {
        return $this->hasMany(LessonContentExplanation::class, ['question_id' => 'question_id']);
    }

    public function getLesson()
    {
        return $this->hasOne(Lesson::class, ['id' => 'lesson_id']);
    }

   


}
