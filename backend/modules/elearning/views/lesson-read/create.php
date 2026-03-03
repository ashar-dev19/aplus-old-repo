<?php

/**
 * @var yii\web\View $this
 * @var backend\modules\elearning\models\LessonRead $model
 */

$this->title = 'Create Lesson Read';
$this->params['breadcrumbs'][] = ['label' => 'Lesson Reads', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lesson-read-create">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
