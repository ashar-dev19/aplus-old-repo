<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "topics".
 *
 * @property int $id
 * @property string $title
 * @property int $subject_id
 * @property int|null $category_id
 * @property string $type
 * @property int|null $grade
 * @property string|null $due_date
 */
class Topics extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'topics';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'subject_id', 'type'], 'required'],
            [['subject_id', 'category_id', 'grade'], 'integer'],
            [['type'], 'string'],
            [['due_date'], 'safe'],
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
            'title' => 'Title',
            'subject_id' => 'Subject ID',
            'category_id' => 'Category ID',
            'type' => 'Type',
            'grade' => 'Grade',
            'due_date' => 'Due Date',
        ];
    }

     /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    public function getLesson()
    {
        return $this->hasMany(Lesson::class, ['chapter_id' => 'id']);
    }

     


}
