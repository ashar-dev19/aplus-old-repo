<?php

/**
 * @var yii\web\View $this
 * @var backend\modules\elearning\models\LessonTestOptions $model
 */

$this->title = 'Update Lesson Test Options: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Lesson Test Options', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="lesson-test-options-update">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
