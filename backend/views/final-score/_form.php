<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\FinalScore $model
 * @var yii\bootstrap4\ActiveForm $form
 */
?>

<div class="final-score-form">
    <?php $form = ActiveForm::begin(); ?>
        <div class="card">
            <div class="card-body">
                <?php echo $form->errorSummary($model); ?>

                <?php echo $form->field($model, 'completed')->textInput() ?>
                <?php echo $form->field($model, 'total_score')->textInput() ?>
                <?php echo $form->field($model, 'complete_date')->textInput() ?>
                <?php echo $form->field($model, 'member_id')->textInput() ?>
                <?php echo $form->field($model, 'final_test_id')->textInput() ?>
                <?php echo $form->field($model, 'score')->textInput() ?>
                
            </div>
            <div class="card-footer">
                <?php echo Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
</div>
