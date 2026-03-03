<?php

namespace app\modules\store\models;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;

use Yii;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property float $sale_price
 * @property float $unit_cost
 * @property float|null $discount
 * @property float $points_price
 * @property string|null $picture
 * @property string|null $web_link
 * @property string|null $review
 * @property int $status
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $creaed_by
 * @property int|null $updated_by
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
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
            [['title', 'sale_price', 'unit_cost', 'points_price', 'status'], 'required'],
            [['description', 'picture', 'review'], 'string'],
            [['sale_price', 'unit_cost', 'discount', 'points_price'], 'number'],
            [['status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['title'], 'string', 'max' => 50],
            [['web_link'], 'string', 'max' => 250],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'sale_price' => 'Sale Price',
            'unit_cost' => 'Unit Cost',
            'discount' => 'Discount',
            'points_price' => 'Points Price',
            'picture' => 'Picture',
            'web_link' => 'Web Link',
            'review' => 'Review',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }
}
