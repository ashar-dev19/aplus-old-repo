<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var backend\modules\elearning\models\Lesson $model
 */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Lessons', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lesson-view">
    <div class="card">
        <div class="card-header">
            <?php echo Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?php echo Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        </div>
        <div class="card-body">
            <?php echo DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    // 'subject_id',
                    ['attribute' => 'subject_id', 'label' => 'Subject', 
                    'value' => function($model){
                        return $model->subject->title;
                    },],
                    // 'lesson_id',
                    ['attribute' => 'id', 'label' => 'Lesson ID'], 
                    'chapter_id',
                    'title',
                    'content:ntext',
                    'video_url:url',

                    'status',
                    // 'created_by',
                    // 'updated_by',
                    // 'created_at',
                    // 'updated_at',
                    
                ],
            ]) ?>
        </div>
    </div>
</div>
