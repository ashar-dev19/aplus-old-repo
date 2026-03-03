<?php

namespace backend\modules\student_management\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\student_management\models\UserSubscription as UserSubscriptionModel;

/**
 * UserSubscription represents the model behind the search form about `backend\modules\student_management\models\UserSubscription`.
 */
class UserSubscription extends UserSubscriptionModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'subscription_id', 'student_id', 'subject_id', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['fee'], 'number'],
            [['expiry_date'], 'safe'],
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
        $query = UserSubscriptionModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'subscription_id' => $this->subscription_id,
            'student_id' => $this->student_id,
            'subject_id' => $this->subject_id,
            'fee' => $this->fee,
            'status' => $this->status,
            'expiry_date' => $this->expiry_date,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        return $dataProvider;
    }
}
