<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Subject".
 *
 * @property int $id
 * @property string $slug
 * @property string $title
 * @property int $status
 * @property int|null $updated_by
 * @property int|null $created_by
 * @property int|null $published_at
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class Subject extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Subject';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['slug', 'title', 'status'], 'required'],
            [['status', 'updated_by', 'created_by', 'published_at', 'created_at', 'updated_at'], 'integer'],
            [['slug', 'title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'slug' => 'Slug',
            'title' => 'Title',
            'status' => 'Status',
            'updated_by' => 'Updated By',
            'created_by' => 'Created By',
            'published_at' => 'Published At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
