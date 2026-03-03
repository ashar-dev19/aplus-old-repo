<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "newsletter".
 *
 * @property int $id
 * @property string $email
 */
// class Newsletter extends \yii\db\ActiveRecord
class Newsletter extends \common\models\Newsletter
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'newsletter';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
         
        return [
            [['email'], 'required'],
            ['email', 'trim'],
            ['email', 'email'],
            ['email', 'string', 'max' => 222],
            ['email', 'unique', 'message' => 'This email is already subscribed.'],  
        ];

    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
        ];
    }

 
 





    
}
