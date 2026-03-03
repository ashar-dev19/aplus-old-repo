<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\modules\store\models\OrderLog $model
 * @var yii\bootstrap4\ActiveForm $form
 */
?>

<div class="order-log-form">
    <?php $form = ActiveForm::begin(); ?>
        <div class="card">
            <div class="card-body">
                <?php echo $form->errorSummary($model); ?>

                <?php echo $form->field($model, 'user_id')->textInput() ?>
                <?php echo $form->field($model, 'student_id')->textInput() ?>
                <?php echo $form->field($model, 'points_redeem')->textInput() ?>
                <?php echo $form->field($model, 'date')->textInput() ?>
                <?php echo $form->field($model, 'payment_id')->textInput() ?>
                <?php echo $form->field($model, 'total_amount')->textInput() ?>
                <?php echo $form->field($model, 'delivery_status')->textInput() ?>
                <?php echo $form->field($model, 'status')->dropdownList(['1' => 'Active', '0' => 'Inactive']) ?>
                
                
            </div>
            <div class="card-footer">
                <?php echo Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
</div>
