<?php

/**
 * @var yii\web\View $this
 * @var backend\modules\elearning\models\LessonRead $model
 */

$this->title = 'Update Lesson Read: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Lesson Reads', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="lesson-read-update">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
