<?php

/**
 * @var yii\web\View $this
 * @var backend\models\ContactQueries $model
 */

$this->title = Yii::t('backend', 'Create {modelClass}', [
    'modelClass' => 'Contact Queries',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Contact Queries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contact-queries-create">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
