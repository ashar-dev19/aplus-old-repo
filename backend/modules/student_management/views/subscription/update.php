<?php

/**
 * @var yii\web\View $this
 * @var backend\modules\student_management\models\Subscription $model
 */

$this->title = 'Update Subscription: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Subscriptions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="subscription-update">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
