<?php

namespace app\modules\store\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\store\models\OrderLog;

/**
 * OrderLogSearch represents the model behind the search form about `app\modules\store\models\OrderLog`.
 */
class OrderLogSearch extends OrderLog
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'student_id', 'payment_id', 'delivery_status', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['points_redeem', 'total_amount'], 'number'],
            [['date'], 'safe'],
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
        $query = OrderLog::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'student_id' => $this->student_id,
            'points_redeem' => $this->points_redeem,
            'date' => $this->date,
            'payment_id' => $this->payment_id,
            'total_amount' => $this->total_amount,
            'delivery_status' => $this->delivery_status,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        return $dataProvider;
    }
}
