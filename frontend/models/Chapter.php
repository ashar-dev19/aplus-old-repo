<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "Chapter".
 *
 * @property int $id
 * @property int $subject_id
 * @property int|null $grade_id
 * @property string $title
 * @property int $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $updated_at
 * @property int|null $created_at
 */
class Chapter extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'chapter';
    }

    

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['subject_id', 'title', 'status'], 'required'],
            [['subject_id', 'grade_id', 'status', 'created_by', 'updated_by', 'updated_at', 'created_at'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['grade_id'], 'exist', 'skipOnError' => true, 'targetClass' => Grade::className(), 'targetAttribute' => ['grade_id' => 'id']],
            [['subject_id'], 'exist', 'skipOnError' => true, 'targetClass' => Subject::className(), 'targetAttribute' => ['subject_id' => 'id']],
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
            'grade_id' => 'Grade ID',
            'title' => 'Title',
            'status' => 'Status',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

     /**
     * Gets query for [[Lessons]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLessons()
    {
        return $this->hasMany(Lesson::className(), ['chapter_id' => 'id']);
    }

    public function getLessonRead()
    {
        return $this->hasMany(LessonRead::class, ['chapter_id' => 'id']);
    }

    
    /**
     * @return \yii\db\ActiveQuery
    */
    public function getGrade()
    {
        return $this->hasOne(Grade::class, ['id' => 'grade_id']);
    }

}
