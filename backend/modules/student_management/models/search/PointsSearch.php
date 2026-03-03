<?php

namespace backend\modules\student_management\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\student_management\models\Points;

/**
 * PointsSearch represents the model behind the search form about `backend\modules\student_management\models\Points`.
 */
class PointsSearch extends Points
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'student_id', 'is_redempt', 'status', 'created_by', 'updated_by', 'created_at', 'update_at'], 'integer'],
            [['points'], 'number'],
            [['details', 'reason', 'redemption_date'], 'safe'],
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
        $query = Points::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'points' => $this->points,
            'student_id' => $this->student_id,
            'is_redempt' => $this->is_redempt,
            'redemption_date' => $this->redemption_date,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'update_at' => $this->update_at,
        ]);

        $query->andFilterWhere(['like', 'details', $this->details])
            ->andFilterWhere(['like', 'reason', $this->reason]);

        return $dataProvider;
    }
}
