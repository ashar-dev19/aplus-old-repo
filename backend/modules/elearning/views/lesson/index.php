<?php

 
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use backend\modules\elearning\models\Subject; 
use backend\modules\elearning\models\Chapter; 

/**
 * @var yii\web\View $this
 * @var backend\modules\elearning\models\search\LessonSearch $searchModel
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = 'Lessons';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lesson-index">
    <div class="card">
        <div class="card-header">
            <?php echo Html::a('Create Lesson', ['create'], ['class' => 'btn btn-success']) ?>
        </div>

        <div class="card-body p-0">
            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    
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

                    // 'id',
                    // 'subject_id',
                    'title',
                    // [
                    //     'attribute' => 'subject_id',
                    //     'label' => 'Subject',
                    //     'value' => function($model) {
                    //         return isset($model->subject) ? $model->subject->title : 'N/A'; // Ensure 'chapter' is loaded
                    //     },
                    //     'filter' => ArrayHelper::map(Subject::find()->all(), 'id', 'title'),
                    // ],

                    // 'lesson_id',
                    
                    [
                        'attribute' => 'chapter_id',
                        'label' => 'Chapter',
                        'value' => function($model) {
                            return isset($model->chapter) ? $model->chapter->title : 'N/A'; // Ensure 'chapter' is loaded
                        },
                        'filter' => ArrayHelper::map(Chapter::find()->all(), 'id', 'title'),
                    ],
                     
                  
                    // 'content:ntext',

                     
                    [
                        'attribute' => 'video_url',
                        'format'    => 'url',
                        'contentOptions' => ['style' => 'max-width:280px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;'],
                    ],



                    // 'status',
                    
                    
                    ['class' => \common\widgets\ActionColumn::class],
                ],

            ]); ?>
    
        </div>
        <div class="card-footer">
            <?php echo getDataProviderSummary($dataProvider) ?>
        </div>
    </div>

</div>
