<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/**
 * @var yii\web\View $this
 * @var backend\models\Notes $model
 * @var yii\bootstrap4\ActiveForm $form
 */
?>

<div class="notes-form">
    <?php $form = ActiveForm::begin(); ?>
        <div class="card">
            <div class="card-body">
                <?php echo $form->errorSummary($model); ?>

                <?php echo $form->field($model, 'id')->textInput() ?>
                <?php echo $form->field($model, 'student_id')->textInput() ?>
                <?php echo $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>
                <?php echo $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
                <?php echo $form->field($model, 'body')->textarea(['rows' => 6]) ?>
                <?php echo $form->field($model, 'grade_id')->textInput() ?>
                <?php echo $form->field($model, 'view')->textInput(['maxlength' => true]) ?>
                <?php echo $form->field($model, 'category_id')->textInput() ?>
                <?php echo $form->field($model, 'thumbnail_base_url')->textInput(['maxlength' => true]) ?>
                <?php echo $form->field($model, 'thumbnail_path')->textInput(['maxlength' => true]) ?>
                <?php echo $form->field($model, 'status')->textInput() ?>
                <?php echo $form->field($model, 'created_by')->textInput() ?>
                <?php echo $form->field($model, 'updated_by')->textInput() ?>
                <?php echo $form->field($model, 'published_at')->textInput() ?>
                <?php echo $form->field($model, 'created_at')->textInput() ?>
                <?php echo $form->field($model, 'updated_at')->textInput() ?>
                
            </div>
            <div class="card-footer">
                <?php echo Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
</div>
