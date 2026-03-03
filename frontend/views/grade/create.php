<?php

/**
 * @var yii\web\View $this
 * @var backend\modules\student_management\models\Grade $model
 */

$this->title = 'Create Grade';
$this->params['breadcrumbs'][] = ['label' => 'Grades', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="grade-create">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
