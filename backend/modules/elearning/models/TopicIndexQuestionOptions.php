<?php


namespace backend\modules\elearning\models;

use Yii;

/**
 * This is the model class for table "topic_index_question_options".
 *
 * @property int $id
 * @property int $question_id
 * @property string $option_value
 */
class TopicIndexQuestionOptions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'topic_index_question_options';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['question_id', 'option_value'], 'required'],
            [['id', 'question_id'], 'integer'],
            [['option_value'], 'string'],
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
            'option_value' => 'Option Value',
        ];
    }
}
