<?php

/**
 * @var yii\web\View $this
 * @var backend\models\Newsletter $model
 */

$this->title = Yii::t('backend', 'Create {modelClass}', [
    'modelClass' => 'Newsletter',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Newsletters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="newsletter-create">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
