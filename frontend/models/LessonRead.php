<?php

namespace frontend\models;
use yii\behaviors\BlameableBehavior;

use yii\behaviors\SluggableBehavior;

use yii\behaviors\TimestampBehavior;

use Yii;

/**
 * This is the model class for table "lesson_read".
 *
 * @property int $id
 * @property int $lesson_id
 * @property int $student_id
 * @property float|null $score
 * @property string $date
 * @property int $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class LessonRead extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lesson_read';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lesson_id', 'student_id', 'date', 'status'], 'required'],
            [['lesson_id', 'student_id', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['score'], 'number'],
            [['date'], 'safe'],
        ];
    }

    public function behaviors()

    {

        return [

            TimestampBehavior::class,
            BlameableBehavior::class,

        ];
    }   


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'lesson_id' => 'Lesson ID',
            'student_id' => 'Student ID',
            'score' => 'Score',
            'date' => 'Date',
            'status' => 'Status',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getLesson()
    {
        return $this->hasOne(Lesson::className(), ['id' => 'lesson_id']);
    }

}
