<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/**
 * @var yii\web\View $this
 * @var backend\models\ContactQueries $model
 * @var yii\bootstrap4\ActiveForm $form
 */
?>

<div class="contact-queries-form">
    <?php $form = ActiveForm::begin(); ?>
        <div class="card">
            <div class="card-body">
                <?php echo $form->errorSummary($model); ?>

                <?php echo $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                <?php echo $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
                <?php echo $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
                <?php echo $form->field($model, 'message')->textarea(['rows' => 6]) ?>
                <?php echo $form->field($model, 'created_at')->textInput() ?>
                
            </div>
            <div class="card-footer">
                <?php echo Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
</div>
