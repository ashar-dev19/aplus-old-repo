<?php

use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var backend\modules\student_management\models\search\SubscriptionSearch $searchModel
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = 'Subscriptions/Packages';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subscription-index">
    <div class="card">
        <div class="card-header">
            <?php echo Html::a('Create Subscription', ['create'], ['class' => 'btn btn-success']) ?>
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

                    // 'id',w
                    'title',
                    'fee:decimal',
                    ['attribute' => 'live_support', 'value' => function($model){return $model->status == 1 ? 'Yes' : 'No';}],
                    'fee_type',
                    'duration',
                    ['attribute' => 'status', 'value' => function($model){return $model->status == 1 ? 'Active' : 'Inactive';}],
                    'created_by',
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
