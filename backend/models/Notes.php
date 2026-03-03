<?php
namespace backend\models;
use yii\helpers\BaseStringHelper;

use Yii;



/**
 * This is the model class for table "notes".
 *
 * @property int $id
 * @property int $student_id
 * @property string $title
 * @property string $body
 * @property int|null $grade_id
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
            [['student_id', 'title', 'body'], 'required'],
            [['student_id', 'grade_id', 'category_id', 'status', 'created_by', 'updated_by', 'published_at', 'created_at', 'updated_at'], 'integer'],
            [['body'], 'string'],
            [['title'], 'string', 'max' => 512],
            [['slug'], 'unique'],
            [['created_at', 'updated_at'], 'default', 'value' => function() { return time(); }],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'student_id' => 'Student ID',
            'slug' => 'Slug',
            'title' => 'Title',
            'body' => 'Body',
            'grade_id' => 'Grade ID',
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

    public function getStudent()
    {
        return $this->hasOne(Student::class, ['id' => 'student_id']);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            // If the slug is empty, generate a slug from the title
            if (empty($this->slug)) {
                // Convert title to a valid slug format (lowercase, spaces to hyphens, special chars removed)
                $this->slug = $this->generateSlug($this->title);
            }

            // Check if slug already exists
            $existingSlug = self::findOne(['slug' => $this->slug]);
            if ($existingSlug) {
                // If slug exists, append timestamp or some unique identifier
                $this->slug = $this->slug . '-' . time();
            }

            return true;
        }
        return false;
    }

    /**
     * Generate a slug from a given title.
     */
    private function generateSlug($title)
    {
        // Convert title to lowercase, replace spaces with hyphens, remove special characters
        $slug = preg_replace('/[^a-z0-9-]/', '', strtolower(str_replace(' ', '-', $title)));
        
        // Ensure the slug is unique by trimming to a maximum length
        return $slug;
    }

}
