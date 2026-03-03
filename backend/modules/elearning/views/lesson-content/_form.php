<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\ActiveForm;
use backend\modules\elearning\models\Subject;
use backend\modules\elearning\models\Lesson;
use backend\modules\elearning\models\Chapter;

/**
 * @var yii\web\View $this
 * @var backend\modules\elearning\models\LessonContent $model
 * @var yii\bootstrap4\ActiveForm $form
 */
?>

<div class="lesson-content-form">
    <?php $form = ActiveForm::begin(); ?>
        <div class="card">
            <div class="card-body">
                <?php echo $form->errorSummary($model); ?>

                <!-- Subject Dropdown -->
                <?php echo $form->field($model, 'subject_id')->dropdownList(
                    ArrayHelper::map(Subject::find()->all(), 'id', 'title'), 
                    ['prompt' => 'Select Subject']
                ) ?>

                <!-- Lesson Dropdown -->
                <?php echo $form->field($model, 'lesson_id')->dropdownList(
                    ArrayHelper::map(Lesson::find()->all(), 'id', 'title'), 
                    ['prompt' => 'Select Lesson']
                ) ?>

                <!-- Chapter Dropdown -->
                <?php echo $form->field($model, 'chapter_id')->dropdownList(
                    ArrayHelper::map(Chapter::find()->all(), 'id', 'title'), 
                    ['prompt' => 'Select Chapter']
                ) ?>

                <!-- Title and Content Fields -->
                <?php echo $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
                <?php echo $form->field($model, 'content')->textarea(['rows' => 6]) ?>

                <!-- Status Dropdown -->
                <?php echo $form->field($model, 'status')->dropdownList(['1' => 'Active', '0' => 'Inactive']) ?>

            </div>
            <div class="card-footer">
                <?php echo Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
</div>
