<?php

/**
 * @var yii\web\View $this
 * @var app\models\FinalScore $model
 */

$this->title = 'Update Final Score: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Final Scores', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="final-score-update">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
