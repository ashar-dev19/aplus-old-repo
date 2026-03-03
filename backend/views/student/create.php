<?php

/**
 * @var yii\web\View $this
 * @var backend\models\Student $model
 */

$this->title = 'Create Student';
$this->params['breadcrumbs'][] = ['label' => 'Students', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="student-create">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
