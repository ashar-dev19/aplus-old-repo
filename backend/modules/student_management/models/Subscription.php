<?php

namespace backend\modules\student_management\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;


/**
 * This is the model class for table "subscription".
 *
 * @property int $id
 * @property string $title
 * @property float $fee
 * @property int|null $live_support
 * @property string|null $fee_type
 * @property string|null $duration
 * @property int $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property UserSubscription[] $userSubscriptions
 */
class Subscription extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'subscription';
    }

    /**
     * {@inheritdoc}
     */
    
    public function rules()
    {
        return [
            [['title', 'fee', 'status'], 'required'],
            [['fee'], 'number'],
            [['live_support', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['title', 'fee_type', 'duration'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */

     public function behaviors()
    {
        return [
            TimestampBehavior::class,
            BlameableBehavior::class,
        ];
    }
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'fee' => 'Fee',
            'live_support' => 'Live Support',
            'fee_type' => 'Fee Type',
            'duration' => 'Duration',
            'status' => 'Status',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[UserSubscriptions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserSubscriptions()
    {
        return $this->hasMany(UserSubscription::className(), ['subscription_id' => 'id']);
    }
}
