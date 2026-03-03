<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\search\TagsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tags';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tags-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Tags', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

     

        <?= \yii\widgets\ListView::widget([
                  'dataProvider' => $dataProvider,
                  'pager' => [
                        'hideOnSinglePage' => true,
                  ],

                  'itemView' => '_item', // Make sure you have this view defined
                  'layout' => '{items}',

            ]) ?>


</div>
