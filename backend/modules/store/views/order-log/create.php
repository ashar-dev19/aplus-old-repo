<?php

/**
 * @var yii\web\View $this
 * @var app\modules\store\models\OrderLog $model
 */

$this->title = Yii::t('backend', 'Create {modelClass}', [
    'modelClass' => 'Order Log',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Order Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-log-create">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
