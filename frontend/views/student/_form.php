<?php



// use backend\modules\UserManagement\models\UserProfile;

// use webvimark\modules\UserManagement\models\User;

use yii\helpers\Html;

use yii\bootstrap4\ActiveForm;



/**

 * @var yii\web\View $this

 * @var backend\modules\student_management\models\Student $model

 * @var yii\bootstrap4\ActiveForm $form

 */

?>



<div class="form">

    <?php $form = ActiveForm::begin(); ?>

        <div class="card">

            <div class="card-body">

                <?php //echo $form->errorSummary($model); ?>
                <?php //$form->field($model, 'id')->hiddenInput()->label(false) ?>
                <?php //echo $form->field($model, 'full_name')->textInput(['maxlength' => true]) ?>
                <?php //echo $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
              
                <?php //echo $form->field($model, 'gender')->dropDownList([
                    //'1' => 'Male', 
                    //'2' => 'Female',
                   // ], ['prompt' => 'Select Gender']);
                ?>
                
                <?php echo $form->field($model, 'details')->fileInput()->label('Profile Image') ?>

            
            </div>

            <div class="card-footer">

                <?php echo Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>

            </div>

        </div>

    <?php ActiveForm::end(); ?>

</div>

