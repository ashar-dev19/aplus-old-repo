<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\FinalScore;

/**
 * FinalScoreSearch represents the model behind the search form about `app\models\FinalScore`.
 */
class FinalScoreSearch extends FinalScore
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'completed', 'total_score', 'member_id', 'final_test_id', 'score'], 'integer'],
            [['complete_date'], 'safe'],
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
        $query = FinalScore::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'completed' => $this->completed,
            'total_score' => $this->total_score,
            'complete_date' => $this->complete_date,
            'member_id' => $this->member_id,
            'final_test_id' => $this->final_test_id,
            'score' => $this->score,
        ]);

        return $dataProvider;
    }
}
