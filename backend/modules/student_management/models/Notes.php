<?php

namespace backend\modules\student_management\models;

use Yii;

/**
 * This is the model class for table "notes".
 *
 * @property int $id
 * @property int $member_id
 * @property string $slug
 * @property string $title
 * @property string $body
 * @property string|null $view
 * @property int|null $category_id
 * @property string|null $thumbnail_base_url
 * @property string|null $thumbnail_path
 * @property int $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $published_at
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class Notes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['student_id', 'slug', 'title', 'body'], 'required'],
            [['student_id', 'category_id', 'status', 'created_by', 'updated_by', 'published_at', 'created_at', 'updated_at'], 'integer'],
            [['body'], 'string'],
            [['slug', 'view'], 'string', 'max' => 255],
            [['title'], 'string', 'max' => 512],
            [['thumbnail_base_url', 'thumbnail_path'], 'string', 'max' => 1024],
            [['slug'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'student_id' => 'Member ID',
            'slug' => 'Slug',
            'title' => 'Title',
            'body' => 'Body',
            'view' => 'View',
            'category_id' => 'Category ID',
            'thumbnail_base_url' => 'Thumbnail Base Url',
            'thumbnail_path' => 'Thumbnail Path',
            'status' => 'Status',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'published_at' => 'Published At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getMember()
    {
        return $this->hasOne(Student::className(), ['id' => 'student_id']);
    }

    public function getGrade()
    {
        return $this->hasOne(Grade::className(), ['id' => 'grade_id']);
    }

}
