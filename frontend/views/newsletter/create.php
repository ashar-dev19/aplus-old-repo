<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Newsletter */

$this->title = Yii::t('frontend', 'Create Newsletter');
$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend', 'Newsletters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="newsletter-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
