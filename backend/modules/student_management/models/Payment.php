<?php

namespace backend\modules\student_management\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;


/**
 * This is the model class for table "payment".
 *
 * @property int $id
 * @property int $user_id
 * @property int $subscription_id
 * @property int $user_subscription_id
 * @property float $amount
 * @property string|null $payment_date
 * @property int $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property UserSubscription $userSubscription
 */
class Payment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payment';
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
     public function rules()
    {
        return [
            [['user_id', 'subscription_id', 'user_subscription_id', 'amount', 'status'], 'required'],
            [['user_id', 'subscription_id', 'user_subscription_id', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['amount'], 'number'],
            [['payment_date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'subscription_id' => 'Subscription ID',
            'user_subscription_id' => 'User Subscription ID',
            'amount' => 'Amount',
            'payment_date' => 'Payment Date',
            'status' => 'Status',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[UserSubscription]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserSubscription()
    {
        return $this->hasOne(UserSubscription::className(), ['id' => 'id']);
    }
}
