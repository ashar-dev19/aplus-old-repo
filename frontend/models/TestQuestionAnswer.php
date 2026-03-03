<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "test_question_answer".
 *
 * @property int $id
 * @property int $question_id
 * @property string $answer
 */
class TestQuestionAnswer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'test_question_answer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['question_id', 'answer'], 'required'],
            [['question_id'], 'integer'],
            [['answer'], 'string'],
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
            'answer' => 'Answer',
        ];
    }

    /**
     * Define a relation to LessonContent based on question_id.
     * Adjust as per your application logic.
     */
    public function getLessonContent()
    {
        return $this->hasOne(LessonContent::className(), ['question_id' => 'question_id']);
    }
    

}
