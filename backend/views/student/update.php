<?php

/**
 * @var yii\web\View $this
 * @var backend\models\Student $model
 */

$this->title = 'Update Student: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Students', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="student-update">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
