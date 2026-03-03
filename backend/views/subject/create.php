<?php

/**
 * @var yii\web\View $this
 * @var app\models\Subject $model
 */

$this->title = 'Create Subject';
$this->params['breadcrumbs'][] = ['label' => 'Subjects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subject-create">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
