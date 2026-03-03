<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/**
 * @var yii\web\View $this
 * @var backend\modules\elearning\models\LessonRead $model
 * @var yii\bootstrap4\ActiveForm $form
 */
?>

<div class="lesson-read-form">
    <?php $form = ActiveForm::begin(); ?>
        <div class="card">
            <div class="card-body">
                <?php echo $form->errorSummary($model); ?>

                <?php echo $form->field($model, 'lesson_id')->textInput() ?>
                <?php echo $form->field($model, 'student_id')->textInput() ?>
                <?php echo $form->field($model, 'score')->textInput() ?>
                <?php echo $form->field($model, 'status')->dropdownList(['1' => 'Active', '0' => 'Inactive']) ?>
                <?php // echo $form->field($model, 'created_by')->textInput() ?>
                <?php // echo $form->field($model, 'updated_by')->textInput() ?>
                <?php // echo $form->field($model, 'created_at')->textInput() ?>
                <?php // echo $form->field($model, 'updated_at')->textInput() ?>
                
            </div>
            <div class="card-footer">
                <?php echo Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
</div>
