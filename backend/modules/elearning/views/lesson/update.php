<?php

/**
 * @var yii\web\View $this
 * @var backend\modules\elearning\models\Lesson $model
 */

$this->title = 'Update Lesson: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Lessons', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="lesson-update">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
