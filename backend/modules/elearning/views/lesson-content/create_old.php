<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\modules\elearning\models\LessonContent */
/* @var $newSubjectModel backend\modules\elearning\models\Subject */
/* @var $newChapterModel backend\modules\elearning\models\Chapter */
/* @var $newLessonModel backend\modules\elearning\models\Lesson */

?>

<?php
// Display flash messages
if (Yii::$app->session->hasFlash('success')) {
    echo '<div class="alert alert-success">' . Yii::$app->session->getFlash('success') . '</div>';
}
if (Yii::$app->session->hasFlash('error')) {
    echo '<div class="alert alert-danger">' . Yii::$app->session->getFlash('error') . '</div>';
}
?>

<div class="lesson-content-form">

<?php $form = ActiveForm::begin(['action' => ['lesson-content/create'], 'id' => 'lesson-content-form']); ?>
<?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>

    
    <?= $form->field($newSubjectModel, 'id')->dropDownList(
        \yii\helpers\ArrayHelper::map($subjects, 'id', 'title'),
        ['prompt' => 'Select Subject', 'id' => 'subject-id']
    )->label('Select Subject') ?>


    
    <?= $form->field($newChapterModel, 'id')->dropDownList(
        [],
        ['prompt' => 'Select Chapter', 'id' => 'chapter-id']
    )->label('Select Chapter') ?>

 
    <?= $form->field($newLessonModel, 'id')->dropDownList(
        [],
        ['prompt' => 'Select Lesson', 'id' => 'lesson-id']
    )->label('Select Lesson ') ?>

    <h3>Create Question</h3>
    <?= $form->field($model, 'title')->textInput(['maxlength' => true])->label('Question') ?>
    <?= $form->field($model, 'content')->textarea(['rows' => 6])->label('Explanation') ?>
    <?= $form->field($model, 'points')->textInput() ?>
    <?= $form->field($model, 'status')->dropDownList([1 => 'Active', 0 => 'Inactive']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
// Generate the URL for chapters and lessons
$ajaxUrlChapters = Url::to(['lesson-content/get-chapters']);
$ajaxUrlLessons = Url::to(['lesson-content/get-lessons']);
?>

<?php
$script = <<< JS
$(document).ready(function() {
    $('#subject-id').change(function() {
        var subjectId = $(this).val();
        
        $.ajax({
            url: '$ajaxUrlChapters',
            type: 'GET',
            data: { id: subjectId },
            success: function(data) {
                $('#chapter-id').empty();
                $('#chapter-id').append('<option value="">Select Chapter</option>');
                $.each(data, function(index, chapter) {
                    $('#chapter-id').append('<option value="' + chapter.id + '">' + chapter.title + '</option>');
                });
            },
            error: function() {
                console.log('Error fetching chapters.');
            }
        });
    });

    $('#chapter-id').change(function() {
        var chapterId = $(this).val();
        
        $.ajax({
            url: '$ajaxUrlLessons',
            type: 'GET',
            data: { id: chapterId },
            success: function(data) {
                $('#lesson-id').empty();
                $('#lesson-id').append('<option value="">Select Lesson</option>');
                $.each(data, function(index, lesson) {
                    $('#lesson-id').append('<option value="' + lesson.id + '">' + lesson.title + '</option>');
                });
            },
            error: function() {
                console.log('Error fetching lessons.');
            }
        });
    });
});
JS;
$this->registerJs($script);
?>
