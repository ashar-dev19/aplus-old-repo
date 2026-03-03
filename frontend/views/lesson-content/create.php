<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\LessonContent */

$this->title = 'Create Lesson Content';
$this->params['breadcrumbs'][] = ['label' => 'Lesson Contents', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lesson-content-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
