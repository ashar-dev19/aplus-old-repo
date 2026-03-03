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
if (Yii::$app->session->hasFlash('success')) {
    echo '<div class="alert alert-success">' . Yii::$app->session->getFlash('success') . '</div>';
}
if (Yii::$app->session->hasFlash('error')) {
    echo '<div class="alert alert-danger">' . Yii::$app->session->getFlash('error') . '</div>';
}
?>

<div class="lesson-content-form">


    <!-- ============ CSV IMPORT FORM (SEPARATE) ============ -->
    <?php
    $csvForm = ActiveForm::begin([
        'action' => ['lesson-content/create'],
        'id' => 'csv-import-form',
        'options' => ['enctype' => 'multipart/form-data'],
        'enableClientValidation' => false, // no model fields here
    ]);
    ?>

    <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>

    <h3>Bulk Upload via CSV</h3>

    <p class="text-muted" style="margin-bottom:8px">
        CSV columns:
        <code>subject_id,chapter_id,lesson_id,question,option1,option2,option3,option4,correct_answer,points</code><br>
        <!-- Agar CSV me IDs khaali hon to neeche selected defaults use honge. -->
    </p>

    <!-- Defaults for CSV -->
    <?= $csvForm->field($newSubjectModel, 'id')->dropDownList(
        \yii\helpers\ArrayHelper::map($subjects, 'id', 'title'),
        ['prompt' => 'Select Subject', 'id' => 'csv-subject-id']
    )->label('Select Subject (default)') ?>

    <?= $csvForm->field($newChapterModel, 'id')->dropDownList(
        [],
        ['prompt' => 'Select Chapter', 'id' => 'csv-chapter-id']
    )->label('Select Chapter (default)') ?>

    <?= $csvForm->field($newLessonModel, 'id')->dropDownList(
        [],
        ['prompt' => 'Select Lesson', 'id' => 'csv-lesson-id']
    )->label('Select Lesson (default)') ?>

    <div class="form-group">
        <label>Upload CSV</label>
        <input type="file" name="csv_file" accept=".csv" class="form-control-file" />
        <div style="margin-top:6px">
            <a href="<?= Url::to('/mnt/data/sample_questions.csv', true) ?>" class="btn btn-link" download>
                Download sample CSV
            </a>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Import CSV', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <hr>



    <!-- ============ SINGLE QUESTION FORM (SEPARATE) ============ -->


    <?php
    $form = ActiveForm::begin([
        'action' => ['lesson-content/create'],
        'id' => 'single-question-form', // yahan validation chahiye to rehne do; CSV ko affect nahi karegi
    ]);
    ?>

    <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>

    <h3>Create Single Question</h3>

    <!-- Subject/Chapter/Lesson for single creation -->
    <?= $form->field($newSubjectModel, 'id')->dropDownList(
        \yii\helpers\ArrayHelper::map($subjects, 'id', 'title'),
        ['prompt' => 'Select Subject', 'id' => 'sq-subject-id']
    )->label('Select Subject') ?>

    <?= $form->field($newChapterModel, 'id')->dropDownList(
        [],
        ['prompt' => 'Select Chapter', 'id' => 'sq-chapter-id']
    )->label('Select Chapter') ?>

    <?= $form->field($newLessonModel, 'id')->dropDownList(
        [],
        ['prompt' => 'Select Lesson', 'id' => 'sq-lesson-id']
    )->label('Select Lesson') ?>

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
$ajaxUrlChapters = Url::to(['lesson-content/get-chapters']);
$ajaxUrlLessons  = Url::to(['lesson-content/get-lessons']);

$js = <<<JS
// CSV form cascading
$('#csv-subject-id').on('change', function() {
    var subjectId = $(this).val();
    $('#csv-chapter-id').empty().append('<option value="">Select Chapter</option>');
    $('#csv-lesson-id').empty().append('<option value="">Select Lesson</option>');
    if (!subjectId) return;
    $.get('$ajaxUrlChapters', {id: subjectId}, function(data) {
        $.each(data, function(_, row) {
            $('#csv-chapter-id').append('<option value="'+row.id+'">'+row.title+'</option>');
        });
    });
});

$('#csv-chapter-id').on('change', function() {
    var chapterId = $(this).val();
    $('#csv-lesson-id').empty().append('<option value="">Select Lesson</option>');
    if (!chapterId) return;
    $.get('$ajaxUrlLessons', {id: chapterId}, function(data) {
        $.each(data, function(_, row) {
            $('#csv-lesson-id').append('<option value="'+row.id+'">'+row.title+'</option>');
        });
    });
});

// Single-question form cascading
$('#sq-subject-id').on('change', function() {
    var subjectId = $(this).val();
    $('#sq-chapter-id').empty().append('<option value="">Select Chapter</option>');
    $('#sq-lesson-id').empty().append('<option value="">Select Lesson</option>');
    if (!subjectId) return;
    $.get('$ajaxUrlChapters', {id: subjectId}, function(data) {
        $.each(data, function(_, row) {
            $('#sq-chapter-id').append('<option value="'+row.id+'">'+row.title+'</option>');
        });
    });
});

$('#sq-chapter-id').on('change', function() {
    var chapterId = $(this).val();
    $('#sq-lesson-id').empty().append('<option value="">Select Lesson</option>');
    if (!chapterId) return;
    $.get('$ajaxUrlLessons', {id: chapterId}, function(data) {
        $.each(data, function(_, row) {
            $('#sq-lesson-id').append('<option value="'+row.id+'">'+row.title+'</option>');
        });
    });
});
JS;

$this->registerJs($js);
?>
