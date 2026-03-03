<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\FinalScore $model
 * @var yii\bootstrap4\ActiveForm $form
 */
?>

<div class="final-score-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?php echo $form->field($model, 'id') ?>
    <?php echo $form->field($model, 'completed') ?>
    <?php echo $form->field($model, 'total_score') ?>
    <?php echo $form->field($model, 'complete_date') ?>
    <?php echo $form->field($model, 'member_id') ?>
    <?php // echo $form->field($model, 'final_test_id') ?>
    <?php // echo $form->field($model, 'score') ?>

    <div class="form-group">
        <?php echo Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?php echo Html::resetButton('Reset', ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
