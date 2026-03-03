<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/**
 * @var yii\web\View $this
 * @var backend\modules\student_management\models\Notification $model
 * @var yii\bootstrap4\ActiveForm $form
 */
?>

<div class="notification-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?php echo $form->field($model, 'id') ?>
    <?php echo $form->field($model, 'client_id') ?>
    <?php echo $form->field($model, 'parent_id') ?>
    <?php echo $form->field($model, 'parent_type') ?>
    <?php echo $form->field($model, 'type') ?>
    <?php // echo $form->field($model, 'medium') ?>
    <?php // echo $form->field($model, 'to_number') ?>
    <?php // echo $form->field($model, 'from_text') ?>
    <?php // echo $form->field($model, 'title') ?>
    <?php // echo $form->field($model, 'contents') ?>
    <?php // echo $form->field($model, 'status') ?>
    <?php // echo $form->field($model, 'created_by') ?>
    <?php // echo $form->field($model, 'updated_by') ?>
    <?php // echo $form->field($model, 'created_at') ?>
    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?php echo Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?php echo Html::resetButton('Reset', ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
