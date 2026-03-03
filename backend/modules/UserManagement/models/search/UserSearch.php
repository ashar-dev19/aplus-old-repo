<?php
namespace backend\modules\UserManagement\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use webvimark\modules\UserManagement\models\User;

class UserSearch extends User
{
    // ——— declare your extra filter fields ———
    public $address;
    public $phone;
    public $sales_person;
    public $sales_person_name;

    public $family_name;
    public $parent_firstname;
    public $parent_lastname;
    public $unit_number;
    public $city;
    public $province;
    public $postal_code;
    public $country;
    public $email_alternate;
    public $phone_alt;
    
    public $fname;
    public $lname;

    public $user_since; 
    public $contract_type;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // integer fields
            [['id','company_id','superadmin','status','email_confirmed','created_at','updated_at'], 'integer'],
            // user-table fields + your new ones all safe for LIKE filters
            [[
                'username','email','registration_ip','gridRoleSearch',
                'address','phone','sales_person',
                'sales_person_name',
                'family_name','parent_firstname','parent_lastname',
                'unit_number','city','province','postal_code','country',
                'email_alternate','phone_alt',
                'fname','lname',
                'contract_type','user_since',
            ], 'safe'],
        ];
    }

    /**
     * Merge parent attributes (the columns of `user`) with your new ones
     */
    public function attributes()
    {
        return array_merge(
            parent::attributes(),
            [
                'address','phone','sales_person',
                'family_name','parent_firstname','parent_lastname',
                'unit_number','city','province','postal_code','country',
                'email_alternate','phone_alt',
                  'fname','lname',
                  'user_since',    
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * Builds an ActiveDataProvider with all your filters.
     */
    public function search($params)
    {
        // alias so we can disambiguate columns
        $query = User::find()->alias('user')
            ->with(['roles'])                 // for the gridRoleSearch
            ->joinWith('profile profile')
             ->joinWith(['salesPerson sp']);    // join your user_profile table

        // non-superadmins only see non-superadmins
        if (!Yii::$app->user->isSuperadmin) {
            $query->andWhere(['user.superadmin'=>0]);
        }

        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'pagination' => ['pageSize'=> Yii::$app->request->cookies->getValue('_grid_page_size',20)],
            'sort'       => ['defaultOrder'=> ['id'=>SORT_DESC]],
        ]);

         
        $dataProvider->sort->attributes['fname'] = [
            'asc'  => ['user.fname' => SORT_ASC],
            'desc' => ['user.fname' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['lname'] = [
            'asc'  => ['user.lname' => SORT_ASC],
            'desc' => ['user.lname' => SORT_DESC],
        ];

         // NEW: sort by sales person FULL NAME
        $dataProvider->sort->attributes['sales_person_name'] = [
            'asc'  => ['sp.fname' => SORT_ASC, 'sp.lname' => SORT_ASC],
            'desc' => ['sp.fname' => SORT_DESC, 'sp.lname' => SORT_DESC],
            'label'=> 'Sales Person',
        ];

        // "User Since" (created_at) sortable
        $dataProvider->sort->attributes['created_at'] = [
            'asc'  => ['user.created_at' => SORT_ASC],
            'desc' => ['user.created_at' => SORT_DESC],
        ];

        // "Contract Type" sortable
        $dataProvider->sort->attributes['contract_type'] = [
            'asc'  => ['user.contract_type' => SORT_ASC],
            'desc' => ['user.contract_type' => SORT_DESC],
        ];



        if (!($this->load($params) && $this->validate())) {
            // no filtering — return all active users
            return $dataProvider;
        }

        // — exact matches —
        $exact = [
            'user.id'             => $this->id,
            'user.superadmin'     => $this->superadmin,
            'user.status'         => $this->status,
            'user.email_confirmed'=> $this->email_confirmed,
            'user.created_at'     => $this->created_at,
            'user.updated_at'     => $this->updated_at,
        ];
        // only apply company_id if the user has explicitly filtered by it
        if ($this->company_id!=='') {
            $exact['user.company_id'] = $this->company_id;
        }
        $query->andFilterWhere($exact);

        // — partial (LIKE) matches for both user and profile columns —
        $query->andFilterWhere(['like','user.username',         $this->username])
              ->andFilterWhere(['like','user.email',            $this->email])
              ->andFilterWhere(['like','user.phone',            $this->phone])
              ->andFilterWhere(['like','user.address',          $this->address])
              ->andFilterWhere(['like','user.sales_person',     $this->sales_person]) 
              
              // (b) NEW: Name-based filter — matches sp.fname, sp.lname and "fname lname"
              ->andFilterWhere([
                  'or',
                  ['like', 'sp.fname', $this->sales_person_name],
                  ['like', 'sp.lname', $this->sales_person_name],
                  ['like', new \yii\db\Expression("CONCAT(sp.fname, ' ', sp.lname)"), $this->sales_person_name],
              ])

              ->andFilterWhere(['like','user.fname',            $this->fname])
              ->andFilterWhere(['like','user.lname',            $this->lname])

              ->andFilterWhere(['like','profile.family_name',       $this->family_name])
              ->andFilterWhere(['like','profile.parent_firstname',  $this->parent_firstname])
              ->andFilterWhere(['like','profile.parent_lastname',   $this->parent_lastname])
              ->andFilterWhere(['like','profile.unit_number',       $this->unit_number])
              ->andFilterWhere(['like','profile.city',              $this->city])
              ->andFilterWhere(['like','profile.province',          $this->province])
              ->andFilterWhere(['like','profile.postal_code',       $this->postal_code])
              ->andFilterWhere(['like','profile.country',           $this->country])
              ->andFilterWhere(['like','profile.email_alternate',   $this->email_alternate])
              ->andFilterWhere(['like','profile.phone_alt',         $this->phone_alt]);

             
                                       
            if (trim((string)$this->user_since) !== '') {
                $q = trim($this->user_since);

                // Hum multiple DATE_FORMATs par LIKE chala rahe hain taa-ke partial matches kaam karein
                $query->andWhere([
                    'or',
                    ['like', new \yii\db\Expression("DATE_FORMAT(FROM_UNIXTIME(user.created_at), '%b %e, %Y')"), $q], // Aug 21, 1970
                    ['like', new \yii\db\Expression("DATE_FORMAT(FROM_UNIXTIME(user.created_at), '%Y-%m-%d')"), $q],  // 1970-08-21
                    ['like', new \yii\db\Expression("DATE_FORMAT(FROM_UNIXTIME(user.created_at), '%Y')"), $q],       // 1970
                    ['like', new \yii\db\Expression("DATE_FORMAT(FROM_UNIXTIME(user.created_at), '%b')"), $q],       // Aug
                    ['like', new \yii\db\Expression("DATE_FORMAT(FROM_UNIXTIME(user.created_at), '%M')"), $q],       // August
                ]);
            }


            // Contract Type filter
            // '__NOTSET__' dropdown me "Not set" option ke liye
            if ($this->contract_type === '__NOTSET__') {
                $query->andWhere(['or', ['user.contract_type' => null], ['user.contract_type' => '']]);
            } else {
                $query->andFilterWhere(['user.contract_type' => $this->contract_type]);
            }


        // filter by role if requested
        if ($this->gridRoleSearch) {
            $query->andFilterWhere([
                Yii::$app->getModule('user-management')->auth_item_table . '.name'
                    => $this->gridRoleSearch,
            ]);
        }

        return $dataProvider;
    }
}
