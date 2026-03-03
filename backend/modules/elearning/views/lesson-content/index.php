<?php

use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var backend\modules\elearning\models\search\LessonContentSearch $searchModel
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = 'Lesson Test Questions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lesson-content-index">
    <div class="card">
        <div class="card-header">
            <?php echo Html::a('Create Test Questions', ['create'], ['class' => 'btn btn-success']) ?>
        </div>

        <div class="card-body p-0">
            <?php echo GridView::widget([
                'layout' => "{items}\n{pager}",
                'options' => [
                    'class' => ['gridview', 'table-responsive'],
                ],
                'tableOptions' => [
                    'class' => ['table', 'text-nowrap', 'table-striped', 'table-bordered', 'mb-0'],
                ],
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    // Display subject, lesson, and chapter names
                    

                    // Title and content with width restrictions
                    [
                        'attribute' => 'title',
                        'contentOptions' => [
                            'style' => 'max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;',
                        ],
                    ],
                    [
                        'attribute' => 'content',
                        'contentOptions' => [
                            'style' => 'max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;',
                        ],
                        'format' => 'ntext',
                    ],

                    [
                        'attribute' => 'subject_id',
                        'value' => function ($model) {
                            return $model->subject ? $model->subject->title : 'N/A'; // Show 'N/A' if subject is not set
                        },
                        'label' => 'Subject Name',
                    ],
                    
                    [
                        'attribute' => 'chapter_id',
                        'value' => function ($model) {
                            return $model->chapter ? $model->chapter->title : 'N/A'; // Show 'N/A' if chapter is not set
                        },
                        'label' => 'Chapter Name',
                    ],
                    [
                        'attribute' => 'lesson_id',
                        'value' => function ($model) {
                            return $model->lesson ? $model->lesson->title : 'N/A'; // Show 'N/A' if lesson is not set
                        },
                        'label' => 'Lesson Name',
                    ],

                    ['class' => \common\widgets\ActionColumn::class],
                ],
            ]); ?>
        </div>
        <div class="card-footer">
            <?php echo getDataProviderSummary($dataProvider) ?>
        </div>
    </div>
</div>
