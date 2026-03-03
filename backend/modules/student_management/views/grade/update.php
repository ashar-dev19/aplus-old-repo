<?php

/**
 * @var yii\web\View $this
 * @var backend\modules\student_management\models\Grade $model
 */

$this->title = 'Update Grade: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Grades', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="grade-update">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
