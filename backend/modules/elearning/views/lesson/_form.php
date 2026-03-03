<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\modules\elearning\models\Chapter; 
use backend\modules\elearning\models\Subject; 

/**
 * @var yii\web\View $this
 * @var backend\modules\elearning\models\Lesson $model
 * @var yii\bootstrap4\ActiveForm $form
 */
?>

<div class="lesson-form">
    <?php $form = ActiveForm::begin(); ?>
        <div class="card">
            <div class="card-body">
                <?php echo $form->errorSummary($model); ?>

                
                <?php echo $form->field($model, 'subject_id')->dropdownList(
                    ArrayHelper::map(Subject::find()->all(), 'id','title' )
                )
                ->label('Subject');
                ?>
               
                <?php 
                    echo $form->field($model, 'chapter_id')->dropdownList(
                        ArrayHelper::map(Chapter::find()->all(), 'id', 'title' ),
                        ['prompt' => 'Select' ]
                )
                ->label('Chapter');
                ?>
                  
               <?php echo $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
                <?php echo $form->field($model, 'content')->textarea(['rows' => 6]) ?>

               <?php
                echo $form->field($model, 'video_url')->textInput([
                    'maxlength'   => true,
                    'placeholder' => 'https://example.com/your-video'
                ])->hint('');
                ?>

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
