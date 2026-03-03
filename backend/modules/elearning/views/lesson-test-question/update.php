<?php

/**
 * @var yii\web\View $this
 * @var backend\modules\elearning\models\LessonTestQuestion $model
 */

$this->title = 'Update Lesson Test Question: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Lesson Test Questions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="lesson-test-question-update">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
