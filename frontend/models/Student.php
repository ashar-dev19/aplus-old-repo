<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "student".
 *
 * @property int $id
 * @property int $parent_id
 * @property string $full_name
 * @property string|null $email
 * @property string|null $details
 * @property int|null $grade_id
 * @property string|null $gender
 * @property string|null $dob
 * @property int|null $live_support
 * @property int $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $updated_at
 * @property int|null $created_at
 */
class Student extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'student';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent_id', 'full_name', 'status'], 'required'],
            [['id', 'parent_id', 'grade_id', 'live_support', 'status', 'created_by', 'updated_by', 'updated_at', 'created_at'], 'integer'],
            [['details'], 'string'],
            [['dob'], 'safe'],
            [['full_name'], 'string', 'max' => 255],
            [['email'], 'string', 'max' => 50],
           
            
            [['gender'], 'in', 'range' => ['1', '2'], 'message' => 'Please select a valid gender.']



        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'Parent ID',
            'full_name' => 'Full Name',
            'email' => 'Email',
            'details' => 'Details',
            'grade_id' => 'Grade ID',
            'gender' => 'Gender',
            'dob' => 'Dob',
            'live_support' => 'Live Support',
            'status' => 'Status',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
    
   
    
}
