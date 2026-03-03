<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/**
 * @var yii\web\View $this
 * @var backend\modules\elearning\models\Grade $model
 * @var yii\bootstrap4\ActiveForm $form
 */
?>

<div class="grade-form">
    <?php $form = ActiveForm::begin(); ?>
        <div class="card">
            <div class="card-body">
                <?php echo $form->errorSummary($model); ?>

                <?php echo $form->field($model, 'id')->textInput() ?>
                <?php echo $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
                <?php echo $form->field($model, 'description')->textarea(['rows' => 6]) ?>
                <?= $form->field($model, 'live_class_link')->textInput(['placeholder' => 'https://meet.google.com/...']) ?>
                <?= $form->field($model, 'live_class_day')->textInput(['placeholder' => 'e.g., Monday & Thursday']) ?>
                <?= $form->field($model, 'live_class_time')->textInput(['placeholder' => 'e.g., 10:00 AM - 11:00 AM']) ?>

                
            </div>
            <div class="card-footer">
                <?php echo Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
</div>
