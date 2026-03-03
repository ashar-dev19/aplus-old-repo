<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker; // Import the DateTimePicker widget

/* @var $this yii\web\View */
/* @var $model frontend\models\Assessment */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Assessment Form';
?>

<div class="assessment-form">
 
        <h2>Book your session now</h2>
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
   
    <div class="form-group my_radios">
        <?= $form->field($model, 'children_count')->radioList([
            '1' => '1',
            '2' => '2',
            '3' => '3+',
            ])->label('How many children do you have?') ?>
    </div>

    <div class="form-group my_radios">
        <?= $form->field($model, 'grades')->radioList([
            'Grades 1 to 2' => 'Grades 1 to 2',
            'Grades 3 to 5' => 'Grades 3 to 5',
            'Grades 6 to 8' => 'Grades 6 to 8',
            'Grades 9 to 11' => 'Grades 9 to 11',
            'Grade 12' => 'Grade 12',
            'Kindergarten' => 'Kindergarten',
            'Other' => 'Other',
        ])->label('What grades are they in?') ?>
    </div>

    <div class="form-group my_radios">
        <?= $form->field($model, 'education_satisfaction')->radioList([
            'Excellent' => 'Excellent',
            'Good' => 'Good',
            'Average' => 'Average',
            'Poor' => 'Poor',
        ])->label('  Are you satisfied with the current level of education your child is recieving?') ?>
      
    </div>

    <div class="form-group my_radios">
        <?= $form->field($model, 'assessment_datetime')->widget(DateTimePicker::classname(), [
            'options' => ['placeholder' => 'Select assessment date and time...'],
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd hh:ii:ss', // Format includes both date and time
                'todayHighlight' => true,
            ],
        ]) ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Book Assessment', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
