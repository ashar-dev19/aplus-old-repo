<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\modules\elearning\models\Subject; 
use backend\modules\student_management\models\Grade;
 
/**
 * @var yii\web\View $this
 * @var backend\modules\elearning\models\Chapter $model
 * @var yii\bootstrap4\ActiveForm $form
 */
?>

<div class="chapter-form">
    <?php $form = ActiveForm::begin(); ?>
        <div class="card">
            <div class="card-body">
                <?php echo $form->errorSummary($model); ?>

                 
                <?php 
                    echo $form->field($model, 'subject_id')->dropdownList(
                        ArrayHelper::map(Subject::find()->all(), 'id', 'title'),
                        ['prompt' => 'Select']
                    )
                    ->label('Subject');
                
                ?>
                 
                <?php 
                    echo $form->field($model, 'grade_id')->dropdownList(
                        ArrayHelper::map(Grade::find()->all(), 'id', 'title'),
                        ['prompt' => 'Select']
                    )
                    ->label('Grade')
                ?>
                <?php echo $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
                <?php echo $form->field($model, 'status')->dropdownList(['1' => 'Active', '0' => 'Inactive']) ?>
                <?php // echo $form->field($model, 'created_by')->textInput() ?>
                <?php // echo $form->field($model, 'updated_by')->textInput() ?>
                <?php // echo $form->field($model, 'updated_at')->textInput() ?>
                <?php // echo $form->field($model, 'created_at')->textInput() ?>
                
            </div>
            <div class="card-footer">
                <?php echo Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
</div>
