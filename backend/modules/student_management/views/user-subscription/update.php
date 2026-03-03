<?php

/**
 * @var yii\web\View $this
 * @var backend\modules\student_management\models\UserSubscription $model
 */

$this->title = 'Update User Subscription: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'User Subscriptions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-subscription-update">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
