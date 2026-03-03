<?php

/**
 * @var yii\web\View $this
 * @var backend\modules\elearning\models\Chapter $model
 */

$this->title = 'Update Chapter: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Chapters', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="chapter-update">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
