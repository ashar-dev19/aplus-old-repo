<?php

namespace backend\modules\elearning\models;

use Yii;

/**
 * This is the model class for table "lesson".
 *
 * @property int $id
 * @property int $subject_id
 * @property int $chapter_id
 * @property int|null $grade_id
 * @property string $title
 * @property string|null $content
 * @property string|null $video_url
 * @property int $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property Chapter $chapter
 * @property LessonRead[] $lessonReads
 * @property LessonTest[] $lessonTests
 */
class Lesson extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lesson';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['subject_id', 'chapter_id', 'title'], 'required'],
            [['subject_id', 'chapter_id', 'grade_id', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['content'], 'string'],
            [['title'], 'string', 'max' => 255],
            ['video_url', 'string', 'max' => 512],
            ['video_url', 'url', 'defaultScheme' => 'https', 'skipOnEmpty' => true],
            [['chapter_id'], 'exist', 'skipOnError' => true, 'targetClass' => Chapter::className(), 'targetAttribute' => ['chapter_id' => 'id']],
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
            'grade_id' => 'Grade ID',
            'title' => 'Title',
            'content' => 'Content',
            'video_url' => 'Video Tutorial URL',
            'status' => 'Status',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Chapter]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getChapter()
    {
        return $this->hasOne(Chapter::className(), ['id' => 'chapter_id']);
    }

    /**
     * Gets query for [[LessonReads]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLessonReads()
    {
        return $this->hasMany(LessonRead::className(), ['lesson_id' => 'id']);
    }

    /**
     * Gets query for [[LessonTests]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLessonTests()
    {
        return $this->hasMany(LessonTest::className(), ['lesson_id' => 'id']);
    }

    public function getSubject()
    {
        return $this->hasOne(Subject::className(), ['id' => 'subject_id']);
    }
    
    public function getLessonContents()
    {
        return $this->hasMany(LessonContent::class, ['lesson_id' => 'id']);
    }
}
