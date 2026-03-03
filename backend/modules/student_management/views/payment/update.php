<?php

/**
 * @var yii\web\View $this
 * @var backend\modules\student_management\models\Payment $model
 */

$this->title = 'Update Payment: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Payments', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="payment-update">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
