<?php

namespace app\modules\store\models;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;

use Yii;

/**
 * This is the model class for table "order_log".
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $student_id
 * @property float|null $points_redeem
 * @property string $date
 * @property int|null $payment_id
 * @property float $total_amount
 * @property int|null $delivery_status
 * @property int $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class OrderLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_log';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            BlameableBehavior::class,
            // [
            //     'class' => SluggableBehavior::class,
            //     'attribute' => 'title',
            //     'immutable' => true,
            // ],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'date', 'total_amount', 'status'], 'required'],
            [['user_id', 'student_id', 'payment_id', 'delivery_status', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['points_redeem', 'total_amount'], 'number'],
            [['date'], 'safe'],
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
            'student_id' => 'Student ID',
            'points_redeem' => 'Points Redeem',
            'date' => 'Date',
            'payment_id' => 'Payment ID',
            'total_amount' => 'Total Amount',
            'delivery_status' => 'Delivery Status',
            'status' => 'Status',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
