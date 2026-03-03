<?php

/**
 * @var yii\web\View $this
 * @var backend\modules\student_management\models\Notification $model
 */

$this->title = 'Create Notification';
$this->params['breadcrumbs'][] = ['label' => 'Notifications', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notification-create">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
