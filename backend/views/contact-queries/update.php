<?php

/**
 * @var yii\web\View $this
 * @var backend\models\ContactQueries $model
 */

$this->title = Yii::t('backend', 'Update {modelClass}: ', [
    'modelClass' => 'Contact Queries',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Contact Queries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="contact-queries-update">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
