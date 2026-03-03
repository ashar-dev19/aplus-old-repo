<?php

/**
 * @var yii\web\View $this
 * @var backend\models\Newsletter $model
 */

$this->title = Yii::t('backend', 'Update {modelClass}: ', [
    'modelClass' => 'Newsletter',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Newsletters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="newsletter-update">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
