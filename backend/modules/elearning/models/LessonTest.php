<?php

namespace backend\modules\elearning\models;

use Yii;

/**
 * This is the model class for table "lesson_test".
 *
 * @property int $id
 * @property int $subject_id
 * @property int $chapter_id
 * @property int $lesson_id
 * @property int $title
 * @property string|null $content
 * @property int $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property Lesson $lesson
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
            [['subject_id', 'chapter_id', 'lesson_id', 'title', 'status'], 'required'],
            [['subject_id', 'chapter_id', 'lesson_id', 'title', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['content'], 'string'],
            [['lesson_id'], 'exist', 'skipOnError' => true, 'targetClass' => Lesson::className(), 'targetAttribute' => ['lesson_id' => 'id']],
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
            'title' => 'Title',
            'content' => 'Content',
            'status' => 'Status',
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
        return $this->hasOne(Lesson::className(), ['id' => 'lesson_id']);
    }
}
