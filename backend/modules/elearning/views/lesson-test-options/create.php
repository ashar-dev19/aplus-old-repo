<?php

/**
 * @var yii\web\View $this
 * @var backend\modules\elearning\models\LessonTestOptions $model
 */

$this->title = 'Create Lesson Test Options';
$this->params['breadcrumbs'][] = ['label' => 'Lesson Test Options', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lesson-test-options-create">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
