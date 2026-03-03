<?php

/**
 * @var yii\web\View $this
 * @var backend\modules\student_management\models\Notes $model
 */

$this->title = 'Update Notes: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Notes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="notes-update">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
