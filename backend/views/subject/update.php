<?php

/**
 * @var yii\web\View $this
 * @var app\models\Subject $model
 */

$this->title = 'Update Subject: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Subjects', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="subject-update">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
