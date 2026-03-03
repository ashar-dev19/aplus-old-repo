<?php

namespace backend\modules\elearning\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\elearning\models\LessonTestAttempt;

/**
 * LessonTestAttemptSearch represents the model behind the search form about `backend\modules\elearning\models\LessonTestAttempt`.
 */
class LessonTestAttemptSearch extends LessonTestAttempt
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'subject_id', 'lesson_test_id', 'student_id', 'status', 'created_by', 'updated_by', 'createde_at', 'updated_at'], 'integer'],
            [['score'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = LessonTestAttempt::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'subject_id' => $this->subject_id,
            'lesson_test_id' => $this->lesson_test_id,
            'student_id' => $this->student_id,
            'score' => $this->score,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'createde_at' => $this->createde_at,
            'updated_at' => $this->updated_at,
        ]);

        return $dataProvider;
    }
}
