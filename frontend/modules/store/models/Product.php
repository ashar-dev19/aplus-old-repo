<?php

namespace app\modules\store\models;

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
 * @property int|null $created_by
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
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'sale_price' => Yii::t('app', 'Sale Price'),
            'unit_cost' => Yii::t('app', 'Unit Cost'),
            'discount' => Yii::t('app', 'Discount'),
            'points_price' => Yii::t('app', 'Points Price'),
            'picture' => Yii::t('app', 'Picture'),
            'web_link' => Yii::t('app', 'Web Link'),
            'review' => Yii::t('app', 'Review'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }
}
