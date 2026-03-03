<?php

/**
 * @var yii\web\View $this
 * @var backend\modules\elearning\models\LessonTestAttempt $model
 */

$this->title = 'Update Lesson Test Attempt: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Lesson Test Attempts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="lesson-test-attempt-update">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
