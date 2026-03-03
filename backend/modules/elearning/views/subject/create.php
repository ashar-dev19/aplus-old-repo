<?php

/**
 * @var yii\web\View $this
 * @var backend\modules\elearning\models\Subject $model
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
