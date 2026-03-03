<?php

/**
 * @var yii\web\View $this
 * @var app\models\FinalScore $model
 */

$this->title = 'Create Final Score';
$this->params['breadcrumbs'][] = ['label' => 'Final Scores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="final-score-create">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
