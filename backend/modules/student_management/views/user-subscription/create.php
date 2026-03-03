<?php

/**
 * @var yii\web\View $this
 * @var backend\modules\student_management\models\UserSubscription $model
 */

$this->title = 'Create User Subscription';
$this->params['breadcrumbs'][] = ['label' => 'User Subscriptions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-subscription-create">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
