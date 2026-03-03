<?php
use yii\helpers\Html;
use yii\grid\GridView;

/** @var $dataProvider \yii\data\ActiveDataProvider */
/** @var $searchModel \webvimark\modules\UserManagement\models\search\UserSearch */
?>

<div class="sales-persons-index">
    <div class="mb-3">
        <?= Html::a('Add Sales Person', ['create', 'user_role' => 'sales-person'], ['class' => 'btn btn-success']) ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns' => [
            // [
            //     'attribute' => 'username',
            //     'label' => 'Name',
            //     'value' => 'username',
            //     'filter' => Html::activeTextInput($searchModel, 'username', [
            //         'class' => 'form-control',
            //         'placeholder' => 'Search by name'
            //     ]),
            // ],
            [
                'attribute' => 'name',
                'label'     => 'Name',
                'value'     => function($m){
                    $full = trim(($m->fname ?? '') . ' ' . ($m->lname ?? ''));
                    return $full !== '' ? $full : ($m->username ?? '');
                },
                'filter'    => Html::activeTextInput($searchModel, 'name', [
                    'class'=>'form-control','placeholder'=>'Search by name'
                ]),
            ],

            [
                'attribute' => 'email',
                'value' => 'email',
                'filter' => Html::activeTextInput($searchModel, 'email', [
                    'class' => 'form-control',
                    'placeholder' => 'Search by email'
                ]),
            ],
            [
                'attribute' => 'phone',  // Add this to show phone number
                'value' => 'phone',
                'filter' => Html::activeTextInput($searchModel, 'phone', [
                    'class' => 'form-control',
                    'placeholder' => 'Search by phone number'
                ]),
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('View Users', ['sales-person-view', 'id' => $model->id], [
                            'class' => 'btn btn-primary',
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>
</div>
