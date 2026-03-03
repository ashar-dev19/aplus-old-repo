<?php

/**
 * @var yii\web\View $this
 * @var backend\modules\elearning\models\LessonTestAttempt $model
 */

$this->title = 'Create Lesson Test Attempt';
$this->params['breadcrumbs'][] = ['label' => 'Lesson Test Attempts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lesson-test-attempt-create">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
