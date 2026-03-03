<?php

/**
 * @var yii\web\View $this
 * @var backend\modules\student_management\models\Subscription $model
 */

$this->title = 'Create Subscription';
$this->params['breadcrumbs'][] = ['label' => 'Subscriptions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subscription-create">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
