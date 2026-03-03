<?php

/**
 * @var yii\web\View $this
 * @var backend\modules\student_management\models\Points $model
 */

$this->title = 'Update Points: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Points', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="points-update">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
