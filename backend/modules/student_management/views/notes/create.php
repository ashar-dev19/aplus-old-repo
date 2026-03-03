<?php

/**
 * @var yii\web\View $this
 * @var backend\modules\student_management\models\Notes $model
 */

$this->title = 'Create Notes';
$this->params['breadcrumbs'][] = ['label' => 'Notes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notes-create">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
