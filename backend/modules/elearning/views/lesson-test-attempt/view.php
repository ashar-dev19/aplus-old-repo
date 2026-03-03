<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var backend\modules\elearning\models\LessonTestAttempt $model
 */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Lesson Test Attempts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lesson-test-attempt-view">
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
                    'lesson_test_id',
                    'student_id',
                    'score',
                    'status',
                    // 'created_by',
                    // 'updated_by',
                    // 'createde_at',
                    // 'updated_at',
                    
                ],
            ]) ?>
        </div>
    </div>
</div>
