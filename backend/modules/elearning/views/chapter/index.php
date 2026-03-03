<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use backend\modules\elearning\models\Subject; 
use backend\modules\student_management\models\Grade;

/**
 * @var yii\web\View $this
 * @var backend\modules\elearning\models\ChapterSearch $searchModel
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = 'Chapters';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="chapter-index">
    <div class="card">
        <div class="card-header">
            <?php echo Html::a('Create Chapter', ['create'], ['class' => 'btn btn-success']) ?>
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

                        
                        'title',
                        
                        [
                            'attribute' => 'subject_id', 
                            'label' => 'Subject', 
                            'value' => function($model){
                                return $model->subject ? $model->subject->title : 'N/A';
                            }, 
                            'filter' => ArrayHelper::map(Subject::find()->all(), 'id', 'title'),
                        ],
                        [
                            'attribute' => 'grade_id', 
                            'label' => 'Grade', 
                            'value' => function($model){
                                return $model->grade ? $model->grade->title : 'N/A';
                            }, 
                            'filter' => ArrayHelper::map(Grade::find()->all(), 'id', 'title'),
                        ],
                        // 'status',
                        // 'created_at:datetime',

                        ['class' => \common\widgets\ActionColumn::class],
                    ],


            ]); ?>
    
        </div>
        <div class="card-footer">
            <?php echo getDataProviderSummary($dataProvider) ?>
        </div>
    </div>

</div>