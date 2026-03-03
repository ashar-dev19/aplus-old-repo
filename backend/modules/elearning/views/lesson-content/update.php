<?php

/**
 * @var yii\web\View $this
 * @var backend\modules\elearning\models\LessonContent $model
 */

$this->title = 'Update Lesson Content: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Lesson Contents', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="lesson-content-update">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
