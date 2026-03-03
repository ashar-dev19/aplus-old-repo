<?php 

namespace common\models;

use Yii;

class Newsletter extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'newsletter';
    }

    public function rules()
    {
        return [
            [['email'], 'required'],
            [['email'], 'string', 'max' => 222],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'    => 'ID',
            'email' => 'Email',
        ];
    }

  

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if (!$insert) { return; } // only on insert

        try {
            $ev = new \common\models\TimelineEvent();
            $ev->application = 'frontend';
            $ev->category    = 'newsletter';
            $ev->event       = 'subscribed';
            $ev->is_read     = 0;
            $ev->created_at  = time();
            $ev->data = json_encode([
                'newsletter_id' => (int)$this->id,
                'email'         => (string)$this->email,
            ], JSON_UNESCAPED_UNICODE);
            $ev->save(false);
        } catch (\Throwable $e) {
            \Yii::error('TimelineEvent create failed: '.$e->getMessage(), __METHOD__);
        }
    }




    
}
