<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\modules\store\models\Product $model
 * @var yii\bootstrap4\ActiveForm $form
 */
?>

<div class="product-form">
    <?php $form = ActiveForm::begin(); ?>
        <div class="card">
            <div class="card-body">
                <?php echo $form->errorSummary($model); ?>

                <?php echo $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
                <?php echo $form->field($model, 'description')->textarea(['rows' => 6]) ?>
                <?php echo $form->field($model, 'sale_price')->textInput() ?>
                <?php echo $form->field($model, 'unit_cost')->textInput() ?>
                <?php echo $form->field($model, 'discount')->textInput() ?>
                <?php echo $form->field($model, 'points_price')->textInput() ?>
                <?php echo $form->field($model, 'picture')->textarea(['rows' => 6]) ?>
                <?php echo $form->field($model, 'web_link')->textInput(['maxlength' => true]) ?>
                <?php echo $form->field($model, 'review')->textarea(['rows' => 6]) ?>
                <?php echo $form->field($model, 'status')->dropdownList(['1' => 'Active', '0' => 'Inactive']) ?>
                
                
            </div>
            <div class="card-footer">
                <?php echo Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
</div>
