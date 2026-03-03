<?php

use backend\modules\UserManagement\models\UserProfile;
use webvimark\modules\UserManagement\models\User;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/**
 * @var yii\web\View $this
 * @var backend\modules\student_management\models\Student $model
 * @var yii\bootstrap4\ActiveForm $form
 */
?>

<div class="student-form">
    <?php $form = ActiveForm::begin(); ?>
        <div class="card">
            <div class="card-body">
                <?php echo $form->errorSummary($model); ?>

                <?php echo $form->field($model, 'parent_id')->dropdownList(
                    yii\helpers\ArrayHelper::map(
                        $user_profiles,
                        'user_id',
                        'full_name'
                    ),
                    ['prompt' => 'Select Parent']
                ); ?>
            
                  
                <?php echo $form->field($model, 'full_name')->textInput(['maxlength' => true]) ?>
                <?php echo $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
                <?php echo $form->field($model, 'details')->textarea(['rows' => 6]) ?>
                <?php //echo $form->field($model, 'grade_id')->dropdownList([\yii\helpers\ArrayHelper::map(
                //$grades,
                //'id',
                //'description')],
               // ['prompt' => 'Select Grade']) ?>
                <?php echo $form->field($model, 'live_support')->dropdownList(['1' => 'Yes', '0' => 'No']) ?>
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
