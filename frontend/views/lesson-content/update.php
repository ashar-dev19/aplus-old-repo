<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\LessonContent */

$this->title = 'Update Lesson Content: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Lesson Contents', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="lesson-content-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
