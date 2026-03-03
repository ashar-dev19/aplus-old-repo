<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\ContactQueries */

$this->title = Yii::t('frontend', 'Create Contact Queries');
$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend', 'Contact Queries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contact-queries-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
