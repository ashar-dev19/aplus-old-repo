<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\modules\store\models\Product $model
 * @var yii\bootstrap4\ActiveForm $form
 */
?>

<div class="product-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?php echo $form->field($model, 'id') ?>
    <?php echo $form->field($model, 'title') ?>
    <?php echo $form->field($model, 'description') ?>
    <?php echo $form->field($model, 'sale_price') ?>
    <?php echo $form->field($model, 'unit_cost') ?>
    <?php // echo $form->field($model, 'discount') ?>
    <?php // echo $form->field($model, 'points_price') ?>
    <?php // echo $form->field($model, 'picture') ?>
    <?php // echo $form->field($model, 'web_link') ?>
    <?php // echo $form->field($model, 'review') ?>
    <?php // echo $form->field($model, 'status') ?>
    <?php // echo $form->field($model, 'created_at') ?>
    <?php // echo $form->field($model, 'updated_at') ?>
    <?php // echo $form->field($model, 'creaed_by') ?>
    <?php // echo $form->field($model, 'updated_by') ?>

    <div class="form-group">
        <?php echo Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?php echo Html::resetButton(Yii::t('backend', 'Reset'), ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
