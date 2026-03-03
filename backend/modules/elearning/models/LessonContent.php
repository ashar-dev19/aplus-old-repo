<?php

namespace backend\modules\elearning\models;

use Yii;

/**
 * This is the model class for table "lesson_content".
 *
 * @property int $id
 * @property int|null $subject_id
 * @property int $lesson_id
 * @property int $chapter_id
 * @property string $title
 * @property string $content
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
            [['subject_id','chapter_id','lesson_id','points','status','question_id'], 'integer'],
            [['title'], 'required'],
            [['content'], 'string'],
            [['title'], 'string', 'max' => 255],
            // duplicate prevention at model-level (optional hard check)
            [['subject_id','chapter_id','lesson_id','title'], 'unique',
                'targetAttribute' => ['subject_id','chapter_id','lesson_id','title'],
                'message' => 'This question already exists in the same Subject/Chapter/Lesson.'
            ],
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
            'lesson_id' => 'Lesson ID',
            'chapter_id' => 'Chapter ID',
            'title' => 'Title',
            'content' => 'Content',
            'points' => 'Points',
            'status' => 'Status',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubject()
    {
        return $this->hasOne(Subject::class, ['id' => 'subject_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLesson()
    {
        return $this->hasOne(Lesson::class, ['id' => 'lesson_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChapter()
    {
        return $this->hasOne(Chapter::class, ['id' => 'chapter_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->created_at = time(); // Set created_at to current timestamp
                $this->created_by = Yii::$app->user->id; // Set created_by to the current user's ID
            }
            $this->updated_at = time(); // Always update updated_at on save
            $this->updated_by = Yii::$app->user->id; // Set updated_by to the current user's ID
            return true;
        }
        return false;
    }
}
