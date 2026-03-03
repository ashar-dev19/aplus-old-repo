<?php
namespace backend\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Student;
use yii\db\Expression;

// CHANGE: total_points attribute add
class StudentSearch extends Student
{
     

    public $parent_email;
    public $parent_last_name;   // NEW
    public $parent_phone;       // NEW
    public $has_parent_email;
    public $total_points; 



    public function rules()
    {
       return [
            [['id','parent_id','grade_id','status','gender'], 'integer'],
            [['full_name','email','dob','parent_email','parent_last_name','parent_phone','has_parent_email'], 'safe'], // NEW fields added
            [['total_points'], 'integer'],
        ];

    }

    public function attributes()
    {
         

        return array_merge(
    parent::attributes(),
        ['parent_email','parent_last_name','parent_phone','has_parent_email','total_points'] // NEW fields added
    );


    }

    public function search($params)
    {
        $query = Student::find()->alias('s'); 

        // relation already alias = parentUser
        $query->joinWith(['parent']);

        $query->joinWith(['grade g']);

        $expr = new Expression("IF(parentUser.id IS NULL OR COALESCE(parentUser.email,'') = '', 0, 1)");

        // CHANGE: points ka subquery (net = earn − redeem)
        $pointsSub = (new \yii\db\Query())
            ->from(\frontend\models\Points::tableName())
            ->select([
                'student_id',
                'total_points' => new Expression("COALESCE(SUM(CASE WHEN is_redempt = 1 THEN -points ELSE points END),0)")
            ])
            ->where(['status' => 1])
            ->groupBy('student_id');

        // CHANGE: LEFT JOIN subquery + select me total_points
        $query->leftJoin(['pt' => $pointsSub], 'pt.student_id = s.id');

        $query->addSelect(['s.*']);
        $query->addSelect(['has_parent_email' => $expr]);
        $query->addSelect(['total_points' => new Expression('pt.total_points')]); 

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'  => [
                'defaultOrder' => ['has_parent_email' => SORT_ASC, 'id' => SORT_DESC],
            ],
            'pagination' => ['pageSize' => 20],
        ]);

        // sorting maps
        $dataProvider->sort->attributes['has_parent_email'] = [
            'asc'  => ['has_parent_email' => SORT_ASC],
            'desc' => ['has_parent_email' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['parent_email'] = [
            'asc'  => ['parentUser.email' => SORT_ASC],
            'desc' => ['parentUser.email' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['grade_id'] = [
            'asc'  => ['g.title' => SORT_ASC],
            'desc' => ['g.title' => SORT_DESC],
        ];
  
        $dataProvider->sort->attributes['total_points'] = [
            'asc'  => ['pt.total_points' => SORT_ASC],
            'desc' => ['pt.total_points' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['parent_last_name'] = [
            'asc'  => ['parentUser.lname' => SORT_ASC],
            'desc' => ['parentUser.lname' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['parent_phone'] = [
            'asc'  => ['parentUser.phone' => SORT_ASC],
            'desc' => ['parentUser.phone' => SORT_DESC],
        ];





        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        // DEFAULT: agar filter me status nahi diya to sirf Active students dikhayen
        if (!isset($params['StudentSearch']['status']) || $params['StudentSearch']['status'] === '') {
            $query->andWhere(['s.status' => 1]);
        } else {
            // user ne explicitly status choose kiya ho to usi ko apply karo
            $query->andFilterWhere(['s.status' => (int)$this->status]);
        }


        // numeric filters
        $query->andFilterWhere([
            's.id'        => $this->id,
            's.parent_id' => $this->parent_id,
            's.grade_id'  => $this->grade_id,
            's.gender'    => $this->gender,
        ]);

        // text filters
        $query->andFilterWhere(['like', 's.full_name', $this->full_name])
              ->andFilterWhere(['like', 's.email', $this->email]);

        if (!empty($this->dob)) {
            $query->andWhere(['s.dob' => $this->dob]);
        }

        if (!empty($this->parent_email)) {
            $query->andFilterWhere(['like', 'parentUser.email', $this->parent_email]);
        }
        
        if (!empty($this->parent_last_name)) {
            $query->andFilterWhere(['like', 'parentUser.lname', $this->parent_last_name]);
        }
        if (!empty($this->parent_phone)) {
            $query->andFilterWhere(['like', 'parentUser.phone', $this->parent_phone]);
        }


        if ($this->has_parent_email !== null && $this->has_parent_email !== '') {
            if ((string)$this->has_parent_email === '1') {
                $query->andWhere([
                    'and',
                    ['IS NOT', 'parentUser.id', null],
                    ['<>', 'parentUser.email', ''],
                ]);
            } else {
                $query->andWhere([
                    'or',
                    ['parentUser.id' => null],
                    ['parentUser.email' => null],
                    ['parentUser.email' => ''],
                ]);
            }
        }

        // CHANGE: total_points par exact match filter
        if ($this->total_points !== null && $this->total_points !== '') {
            $query->andWhere(['pt.total_points' => (int)$this->total_points]);
        }

        return $dataProvider;
    }
}
