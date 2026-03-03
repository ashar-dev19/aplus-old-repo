<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Change Password';
?>

<div class="container mt-5">
    <h3><?= Html::encode($this->title) ?></h3>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'oldPassword')->passwordInput(['placeholder' => 'Enter your old password']) ?>
    <?= $form->field($model, 'newPassword')->passwordInput(['placeholder' => 'Enter new password']) ?>
    <?= $form->field($model, 'repeatPassword')->passwordInput(['placeholder' => 'Confirm new password']) ?>

    <div class="form-group mt-3">
        <?= Html::submitButton('Change Password', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
