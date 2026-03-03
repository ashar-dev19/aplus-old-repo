<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\modules\store\models\OrderLog $model
 * @var yii\bootstrap4\ActiveForm $form
 */
?>

<div class="order-log-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?php echo $form->field($model, 'id') ?>
    <?php echo $form->field($model, 'user_id') ?>
    <?php echo $form->field($model, 'student_id') ?>
    <?php echo $form->field($model, 'points_redeem') ?>
    <?php echo $form->field($model, 'date') ?>
    <?php // echo $form->field($model, 'payment_id') ?>
    <?php // echo $form->field($model, 'total_amount') ?>
    <?php // echo $form->field($model, 'delivery_status') ?>
    <?php // echo $form->field($model, 'status') ?>
    <?php // echo $form->field($model, 'created_by') ?>
    <?php // echo $form->field($model, 'updated_by') ?>
    <?php // echo $form->field($model, 'created_at') ?>
    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?php echo Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?php echo Html::resetButton(Yii::t('backend', 'Reset'), ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
