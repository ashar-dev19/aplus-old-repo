<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "lesson_content_explanation".
 *
 * @property int $id
 * @property int $question_id
 * @property string $explanation
 * @property int $status
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_at
 * @property int $updated_at
 */
class LessonContentExplanation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lesson_content_explanation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'question_id', 'explanation', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'required'],
            [['id', 'question_id', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['explanation'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'question_id' => 'Question ID',
            'explanation' => 'Explanation',
            'status' => 'Status',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
