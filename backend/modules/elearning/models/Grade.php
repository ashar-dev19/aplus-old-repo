<?php

namespace backend\modules\elearning\models;

use Yii;

/**
 * This is the model class for table "grade".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string|null $live_class_link
 * @property string|null $live_class_time
 * @property string|null $live_class_day
 */
class Grade extends \yii\db\ActiveRecord
{
    


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'grade';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'title', 'description'], 'required'],
            [['id'], 'integer'],
            [['description'], 'string'],
            [['title', 'live_class_link', 'live_class_time', 'live_class_day'], 'string', 'max' => 255],
            ['live_class_link', 'url'],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'live_class_link' => 'Google Meet Link',
            'live_class_time' => 'Live Class Time',
            'live_class_day' => 'Live Class Day',
        ];
    }


    public function getChapters()
    {
        return $this->hasMany(Chapter::class, ['grade_id' => 'id']);
    }

    /**
     * Get related Subjects via Chapters
     */
    public function getSubjects()
    {
        return $this->hasMany(Subject::class, ['id' => 'subject_id'])
            ->via('chapters'); // Indirect relation through Chapters
    }
 

    public function getSubjectTitles()
{
    return implode(', ', array_map(fn($subject) => $subject->title, $this->subjects));
}




}
