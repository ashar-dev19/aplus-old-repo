<?php

use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var backend\modules\student_management\models\search\UserSubscription $searchModel
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = 'User Subscriptions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-subscription-index">
    <div class="card">
        <div class="card-header">
            <?php echo Html::a('Create User Subscription', ['create'], ['class' => 'btn btn-success']) ?>
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

                    'id',
                    ['attribute'=>'user_id', 'label'=>'Parent' ],
                    'subscription_id',
                    // 'student_id' //OSB: add this later
                    // 'subject_id', //OSB: add this later
                    ['attribute'=>'fee', 'label'=>'Payment Amount' ],
                    'status:statuslabel',
                    'expiry_date:date',
                    // 'created_by', //OSB: add this later
                    // 'updated_by',
                    'created_at:date',
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
