<?php

namespace backend\modules\elearning\models;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;


use Yii;
use backend\modules\student_management\models\Grade;

/**
 * This is the model class for table "chapter".
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
 *
 * @property Grade $grade
 * @property Lesson[] $lessons
 * @property Subject $subject
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
            [['title'], 'string', 'max' => 50],
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

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            BlameableBehavior::class,
            // [
            //     'class' => SluggableBehavior::class,
            //     'attribute' => 'title',
            //     'immutable' => true,
            // ],

        ];
    }


    /**
     * Gets query for [[Grade]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGrade()
    {
        return $this->hasOne(Grade::className(), ['id' => 'grade_id']);
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

    /**
     * Gets query for [[Subject]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubject()
    {
        return $this->hasOne(Subject::className(), ['id' => 'subject_id']);
    }
 

}
