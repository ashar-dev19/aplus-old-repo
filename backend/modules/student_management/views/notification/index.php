<?php

use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var backend\modules\student_management\models\search\NotificationSearch $searchModel
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = 'Notifications';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notification-index">
    <div class="card">
        <div class="card-header">
            <?php echo Html::a('Create Notification', ['create'], ['class' => 'btn btn-success']) ?>
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
                ['class' => 'yii\grid\CheckboxColumn'],
                ['class' => 'yii\grid\SerialColumn'],

                    'id',
                    'client_id',
                    ['attribute' => 'client_id', 'label' => 'Student', 
                    'value' => function($model){
                        return $model->student->full_name;
                    }, ],
                    // 'filter' => ArrayHelper::map(Grade::find()->all(), 'id', 'title'),],
                    // 'parent_id',
                    ['attribute' => 'parent_id', 'label' => 'Parent', 
                    'value' => function($model){
                        return $model->parent->firstname.' '.$model->parent->lastname;
                    }, ],
                    // 'parent_type',
                    // 'type',
                    // 'medium',
                    'to_number',
                    'from_text',
                    ['attribute' => 'title', 'label' => 'Title'], 
                    'contents:ntext',
                    'status',
                    // 'created_by',
                    // 'updated_by',
                    // 'created_at',
                    // 'updated_at',
                    
                    ['class' => \common\widgets\ActionColumn::class],
                ],
            ]); ?>
    
        </div>
        <div class="card-footer">
            <?php echo getDataProviderSummary($dataProvider) ?>
        </div>
    </div>

</div>
