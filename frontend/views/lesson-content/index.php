<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\search\LessonContentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Lesson Contents';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lesson-content-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php 
        // Check if the current user is the one who should have access to create lesson content
        if (Yii::$app->user->identity->username === 'danish') {
            echo Html::a('Create Lesson Content', ['create'], ['class' => 'btn btn-success']);
        }
        ?>
    </p>

    <?php Pjax::begin(); ?>
     
  

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => function ($model, $key, $index, $widget) {
            return Html::a(Html::encode($model->title), ['view', 'id' => $model->id]);
        },
    ]) ?>

    <?php Pjax::end(); ?>

</div>
