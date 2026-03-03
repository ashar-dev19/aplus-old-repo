<?php

/**
 * @var yii\web\View $this
 * @var backend\modules\elearning\models\Grade $model
 */

$this->title = Yii::t('backend', 'Create {modelClass}', [
    'modelClass' => 'Grade',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Grades'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="grade-create">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
