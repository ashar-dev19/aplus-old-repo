<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "assessment".
 *
 * @property int $id
 * @property string|null $first_name
 * @property string $last_name
 * @property string|null $phone
 * @property string $email
 * @property string|null $children_count
 * @property string|null $grades
 * @property string|null $education_satisfaction
 * @property string|null $assessment_datetime
 * @property string|null $created_at
 */
class Assessment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'assessment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['last_name', 'email'], 'required'],
            [['assessment_datetime', 'created_at'], 'safe'],
            [['first_name', 'last_name'], 'string', 'max' => 100],
            [['phone'], 'string', 'max' => 50],
            [['email'], 'string', 'max' => 250],
            [['children_count'], 'string', 'max' => 10],
            [['grades', 'education_satisfaction'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'phone' => 'Phone',
            'email' => 'Email',
            'children_count' => 'Children Count',
            'grades' => 'Grades',
            'education_satisfaction' => 'Education Satisfaction',
            'assessment_datetime' => 'Assessment Datetime',
            'created_at' => 'Created At',
        ];
    }
  

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        try {
            $ev = new \common\models\TimelineEvent();
            $ev->application    = 'frontend';
            $ev->category       = 'assessment';
            $ev->event          = 'booked';
            $ev->is_read        = 0;
            $ev->assessment_id  = (int)$this->id;
            $ev->created_at     = time();
            $ev->data = json_encode([
                'assessment_id' => (int)$this->id,
                // keep any fields you want quick access to in the JSON:
                'name'          => trim(($this->first_name ?? '').' '.($this->last_name ?? '')),
                'email'         => (string)$this->email,
                'phone'         => (string)$this->phone,
                'booked_by'     => (string)(Yii::$app->user->identity->username ?? 'guest'),
            ], JSON_UNESCAPED_UNICODE);

            $ev->save(false);
        } catch (\Throwable $e) {
            Yii::error('TimelineEvent create failed: '.$e->getMessage(), __METHOD__);
        }
    }




}
