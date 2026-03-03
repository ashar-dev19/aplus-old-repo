<?php

/**
 * @var yii\web\View $this
 * @var backend\modules\elearning\models\LessonTest $model
 */

$this->title = 'Create Lesson Test';
$this->params['breadcrumbs'][] = ['label' => 'Lesson Tests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lesson-test-create">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
