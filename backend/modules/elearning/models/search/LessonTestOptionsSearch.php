<?php

namespace backend\modules\elearning\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\elearning\models\LessonTestOptions;

/**
 * LessonTestOptionsSearch represents the model behind the search form about `backend\modules\elearning\models\LessonTestOptions`.
 */
class LessonTestOptionsSearch extends LessonTestOptions
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'question_id', 'subject_id', 'test_id', 'lesson_id', 'is_clue', 'is_answer', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['title', 'content'], 'safe'],
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
        $query = LessonTestOptions::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'question_id' => $this->question_id,
            'subject_id' => $this->subject_id,
            'test_id' => $this->test_id,
            'lesson_id' => $this->lesson_id,
            'is_clue' => $this->is_clue,
            'is_answer' => $this->is_answer,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'content', $this->content]);

        return $dataProvider;
    }
}
