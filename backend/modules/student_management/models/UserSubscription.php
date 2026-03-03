<?php

namespace backend\modules\student_management\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "user_subscription".
 *
 * @property int $id
 * @property int $user_id
 * @property int $subscription_id
 * @property int|null $student_id
 * @property int $subject_id
 * @property float $fee
 * @property int $status
 * @property string|null $expiry_date
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property Payment $id0
 * @property Subscription $subscription
 */
class UserSubscription extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_subscription';
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
            [['user_id', 'subscription_id', 'subject_id', 'fee', 'status'], 'required'],
            [['user_id', 'subscription_id', 'student_id', 'subject_id', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['fee'], 'number'],
            [['expiry_date'], 'safe'],
            // [['id'], 'exist', 'skipOnError' => true, 'targetClass' => Payment::className(), 'targetAttribute' => ['id' => 'id']],
            [['subscription_id'], 'exist', 'skipOnError' => true, 'targetClass' => Subscription::className(), 'targetAttribute' => ['subscription_id' => 'id']],
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
            'student_id' => 'Student ID',
            'subject_id' => 'Subject ID',
            'fee' => 'Fee',
            'status' => 'Status',
            'expiry_date' => 'Expiry Date',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Id0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getId0()
    {
        return $this->hasOne(Payment::className(), ['id' => 'id']);
    }

    /**
     * Gets query for [[Subscription]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubscription()
    {
        return $this->hasOne(Subscription::className(), ['id' => 'subscription_id']);
    }
}
