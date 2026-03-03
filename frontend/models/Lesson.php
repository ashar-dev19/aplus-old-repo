<?php

namespace frontend\models;

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
 * @property LessonContent[] $lessonContent
 */
class Lesson extends \yii\db\ActiveRecord
{
    public $progress;

    public static function tableName()
    {
        return 'lesson';
    }

    public function rules()
    {
        return [
            [['subject_id', 'chapter_id', 'title'], 'required'],
            [['subject_id', 'chapter_id', 'grade_id', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['content'], 'string'],
            [['video_url'], 'string', 'max' => 500],
            [['title'], 'string', 'max' => 255],
            [['chapter_id'], 'exist', 'skipOnError' => true, 'targetClass' => Chapter::className(), 'targetAttribute' => ['chapter_id' => 'id']],
        ];
    }

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

    public function getChapter()
    {
        return $this->hasOne(Chapter::className(), ['id' => 'chapter_id']);
    }

    public function getLessonReads()
    {
        return $this->hasMany(LessonRead::className(), ['lesson_id' => 'id']);
    }

    public function getSubject()
    {
        return $this->hasOne(Subject::className(), ['id' => 'subject_id']);
    }

    public function getLessonTests()
    {
        return $this->hasMany(LessonTest::className(), ['lesson_id' => 'id']);
    }

    public function getLessonContent()
    {
        return $this->hasMany(LessonContent::className(), ['lesson_id' => 'id']);
    }

    /**
     * Calculate the overall lesson progress for all users
     *
     * @return int progress percentage
     */
    public function calculateProgress()
    {
        $totalContent = $this->getLessonContent()->count(); // Use count() directly for efficiency
        $completedContent = $this->getLessonContent()
            ->joinWith('lessonReads') // Ensure joining with lessonReads if needed
            ->where(['lesson_read.status' => 1]) // Completed content
            ->count(); 
        return ($totalContent > 0) ? round(($completedContent / $totalContent) * 100) : 0;
    }

    /**
     * Calculate the lesson progress for a specific student
     * 
     * @param int $studentId
     * @return int progress percentage
     */
    public function calculateLessonProgress($studentId)
    {
        $totalContent = $this->getLessonContent()->count(); // Total content in the lesson
        $completedContent = LessonRead::find()
            ->where(['lesson_id' => $this->id, 'student_id' => $studentId, 'status' => 1]) // Status 1 means completed
            ->count(); // Count completed lessons

        // Return progress as a percentage
        return ($totalContent > 0) ? round(($completedContent / $totalContent) * 100) : 0;
    }

    /**
     * Get the progress as a property (virtual getter)
     *
     * @param int $studentId
     * @return int progress percentage
     */
    public function getProgressForStudent($studentId)
    {
        return $this->calculateLessonProgress($studentId);
    }
}
