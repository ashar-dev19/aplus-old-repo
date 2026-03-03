<?php

/**
 * @var yii\web\View $this
 * @var app\modules\store\models\Product $model
 */

$this->title = Yii::t('backend', 'Create {modelClass}', [
    'modelClass' => 'Product',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-create">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
