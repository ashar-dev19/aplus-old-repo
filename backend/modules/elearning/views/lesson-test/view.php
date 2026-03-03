<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var backend\modules\elearning\models\LessonTest $model
 */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Lesson Tests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lesson-test-view">
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
                    'subject_id',
                    'chapter_id',
                    'lesson_id',
                    'title',
                    'content:ntext',
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
