<?php

/**
 * @var yii\web\View $this
 * @var backend\modules\elearning\models\Lesson $model
 */

$this->title = 'Create Lesson';
$this->params['breadcrumbs'][] = ['label' => 'Lessons', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lesson-create">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
