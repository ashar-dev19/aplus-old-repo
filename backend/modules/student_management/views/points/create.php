<?php

/**
 * @var yii\web\View $this
 * @var backend\modules\student_management\models\Points $model
 */

$this->title = 'Create Points';
$this->params['breadcrumbs'][] = ['label' => 'Points', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="points-create">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
