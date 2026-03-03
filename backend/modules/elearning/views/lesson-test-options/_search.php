<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/**
 * @var yii\web\View $this
 * @var backend\modules\elearning\models\LessonTestOptions $model
 * @var yii\bootstrap4\ActiveForm $form
 */
?>

<div class="lesson-test-options-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?php echo $form->field($model, 'id') ?>
    <?php echo $form->field($model, 'question_id') ?>
    <?php echo $form->field($model, 'subject_id') ?>
    <?php echo $form->field($model, 'test_id') ?>
    <?php echo $form->field($model, 'lesson_id') ?>
    <?php // echo $form->field($model, 'title') ?>
    <?php // echo $form->field($model, 'is_clue') ?>
    <?php // echo $form->field($model, 'is_answer') ?>
    <?php // echo $form->field($model, 'content') ?>
    <?php // echo $form->field($model, 'status') ?>
    <?php // echo $form->field($model, 'created_by') ?>
    <?php // echo $form->field($model, 'updated_by') ?>
    <?php // echo $form->field($model, 'created_at') ?>
    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?php echo Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?php echo Html::resetButton('Reset', ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
