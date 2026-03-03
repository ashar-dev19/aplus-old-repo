<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "final_score".
 *
 * @property int $id
 * @property int $completed
 * @property int $total_score
 * @property string $complete_date
 * @property int $member_id
 * @property int $final_test_id
 * @property int $score
 */
class FinalScore extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'final_score';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['completed', 'total_score', 'complete_date', 'member_id', 'final_test_id', 'score'], 'required'],
            [['completed', 'total_score', 'member_id', 'final_test_id', 'score'], 'integer'],
            [['complete_date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'completed' => 'Completed',
            'total_score' => 'Total Score',
            'complete_date' => 'Complete Date',
            'member_id' => 'Member ID',
            'final_test_id' => 'Final Test ID',
            'score' => 'Score',
        ];
    }
}
