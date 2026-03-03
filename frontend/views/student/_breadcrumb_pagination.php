<?php
use yii\widgets\Breadcrumbs;
use yii\widgets\LinkPager;

// Breadcrumb
// echo Breadcrumbs::widget([
//     'links' => [
//         ['label' => 'Home', 'url' => ['/site/index']],
//         ['label' => 'Students'],
//     ],
// ]);

// Pagination
echo LinkPager::widget([
    'pagination' => $dataProvider->pagination,
]);
?>