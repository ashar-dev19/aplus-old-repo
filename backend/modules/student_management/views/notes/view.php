<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var backend\modules\student_management\models\Notes $model
 */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Notes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notes-view">
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
                    'student_id',
                    'slug',
                    'title',
                    'body:ntext',
                    'view',
                    'category_id',
                    'thumbnail_base_url:url',
                    'thumbnail_path',
                    'status',
                    'created_by',
                    'updated_by',
                    'published_at:date',
                    'created_at:date',
                    'updated_at:date',
                    
                ],
            ]) ?>
        </div>
    </div>
</div>
