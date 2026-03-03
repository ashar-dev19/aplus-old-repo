<?php

/**
 * @var yii\web\View $this
 * @var backend\models\Notes $model
 */

$this->title = Yii::t('backend', 'Create {modelClass}', [
    'modelClass' => 'Notes',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Notes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notes-create">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
