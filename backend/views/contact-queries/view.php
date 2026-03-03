<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var backend\models\ContactQueries $model
 */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Contact Queries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contact-queries-view">
    <div class="card">
        <div class="card-header">
            <?php //echo Html::a(Yii::t('backend', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?php //echo Html::a(Yii::t('backend', 'Delete'), ['delete', 'id' => $model->id], [
                //'class' => 'btn btn-danger',
                //'data' => [
                    //'confirm' => Yii::t('backend', 'Are you sure you want to delete this item?'),
                   // 'method' => 'post',
                //],
            //]) ?>
        </div>
        <div class="card-body">
            <?php echo DetailView::widget([
                'model' => $model,
                'attributes' => [
                    //'id',
                    'name',
                    'email:email',
                    'phone',
                    'message:ntext',
                    'created_at',
                    
                ],
            ]) ?>
        </div>
    </div>
</div>
