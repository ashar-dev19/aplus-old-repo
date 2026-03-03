<?php

namespace backend\modules\student_management\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\student_management\models\Notification;

/**
 * NotificationSearch represents the model behind the search form about `backend\modules\student_management\models\Notification`.
 */
class NotificationSearch extends Notification
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'client_id', 'parent_id', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['parent_type', 'type', 'medium', 'to_number', 'from_text', 'title', 'contents'], 'safe'],
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
        $query = Notification::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'client_id' => $this->client_id,
            'parent_id' => $this->parent_id,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'parent_type', $this->parent_type])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'medium', $this->medium])
            ->andFilterWhere(['like', 'to_number', $this->to_number])
            ->andFilterWhere(['like', 'from_text', $this->from_text])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'contents', $this->contents]);

        return $dataProvider;
    }
}
