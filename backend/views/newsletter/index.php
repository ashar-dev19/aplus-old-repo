<?php

use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var backend\models\search\NewsletterSearch $searchModel
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = Yii::t('backend', 'Newsletters');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="newsletter-index">
    <div class="card">
        <div class="card-header">
            <?php //echo Html::a(Yii::t('backend', 'Create {modelClass}', [
                //'modelClass' => 'Newsletter',
            //]), ['create'], ['class' => 'btn btn-success']) ?>
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
                    'email:email',
                    
                    [
                        'class' => \yii\grid\ActionColumn::class,
                        'template' => '{view} {delete}',
                        'buttons' => [
                            'view' => function ($url, $model) {
                                return Html::a('<i class="fa fa-eye"></i>', $url, [
                                    'class' => 'btn btn-sm btn-primary',
                                    'title' => Yii::t('backend', 'View'),
                                ]);
                            },
                            'delete' => function ($url, $model) {
                                return Html::a('<i class="fa fa-trash"></i>', $url, [
                                    'class' => 'btn btn-sm btn-danger',
                                    'title' => Yii::t('backend', 'Delete'),
                                    'data'  => [
                                        'confirm' => 'Delete this email from newsletter?',
                                        'method'  => 'post',
                                    ],
                                ]);
                            },
                        ],
                    ],

                ],
            ]); ?>
    
        </div>
        <div class="card-footer">
            <?php echo getDataProviderSummary($dataProvider) ?>
        </div>
    </div>

</div>
