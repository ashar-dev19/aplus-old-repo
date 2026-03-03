<?php

/**
 * @var yii\web\View $this
 * @var backend\modules\elearning\models\LessonTest $model
 */

$this->title = 'Update Lesson Test: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Lesson Tests', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="lesson-test-update">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
