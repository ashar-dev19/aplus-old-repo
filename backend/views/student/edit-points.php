<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var $student backend\models\Student */

$this->title = 'Edit Points for ' . $student->full_name;
$this->params['breadcrumbs'][] = ['label' => 'Students', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="edit-points">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <div class="form-group">
        <?= Html::label('Total Points', 'points') ?>
        <?= Html::input('number', 'points', $student->getTotalPoints(), ['class' => 'form-control']) ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
