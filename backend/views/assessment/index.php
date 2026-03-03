<?php

use yii\grid\GridView;
use yii\helpers\Html;

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        // 'id',
        'first_name',
        'last_name',
        'phone',
        'email:email',
        'created_at:datetime',

        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view} {delete}',
            'buttons' => [
                'delete' => function ($url, $model, $key) {
                    return Html::a('<i class="fa fa-trash"></i>', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-sm btn-danger',
                        'title' => 'Delete',
                        'data' => [
                            'confirm' => 'Delete this assessment?',
                            'method' => 'post',
                        ],
                    ]);
                },
            ],
        ],
    ],

]);

?>
