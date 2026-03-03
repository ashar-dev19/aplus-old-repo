<?php

/**
 * @var yii\web\View $this
 * @var backend\modules\elearning\models\LessonTestQuestion $model
 */

$this->title = 'Create Lesson Test Question';
$this->params['breadcrumbs'][] = ['label' => 'Lesson Test Questions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lesson-test-question-create">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
