<?php

use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var backend\modules\student_management\models\search\PointsSearch $searchModel
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = 'Points';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="points-index">
    <div class="card">
        <div class="card-header">
            <?php echo Html::a('Create Points', ['create'], ['class' => 'btn btn-success']) ?>
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
                    'points',
                    // 'student_id',
                    ['attribute' => 'student_id', 'label' => 'Student', 
                    'value' => function($model){
                        return $model->student->full_name;
                    }, ],
                    'details:ntext',
                    // ['attribute' => 'details', 'label' => 'Parent', 
                    // 'value' => function($model){
                    //     if ($model->parent->lastname != 'NULL') {
                    //       return $model->parent->lastname;} //.' '.$model->parent->lastname;
                    // }, ],
                    'is_redempt',
                    // [$form->field($model, 'redemption_date')->textInput()],
                    // 'reason:ntext',
                    // 'redemption_date',
                    // 'status',
                    // 'created_by',
                    'updated_by:datetime',
                    // 'created_at',
                    'update_at:datetime',
                    
                    ['class' => \common\widgets\ActionColumn::class],
                ],
            ]); ?>
    
        </div>
        <div class="card-footer">
            <?php echo getDataProviderSummary($dataProvider) ?>
        </div>
    </div>

</div>
