<?php

/**
 * @var yii\web\View $this
 * @var backend\modules\student_management\models\Notification $model
 */

$this->title = 'Update Notification: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Notifications', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="notification-update">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
