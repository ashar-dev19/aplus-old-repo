<?php

/**
 * @var yii\web\View $this
 * @var backend\modules\student_management\models\Payment $model
 */

$this->title = 'Create Payment';
$this->params['breadcrumbs'][] = ['label' => 'Payments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payment-create">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
