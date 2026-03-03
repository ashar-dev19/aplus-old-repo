<?php
use yii\widgets\DetailView;

/** @var \frontend\models\Assessment $model */

$this->title = 'Assessment – '.$model->first_name.' '.$model->last_name;
$this->params['breadcrumbs'][] = ['label' => 'Assessments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">
  <div class="card-body">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            // 'id',
            'first_name',
            'last_name',
            'email:email',
            'phone',
            'children_count',
            'grades',
            'education_satisfaction',
            'assessment_datetime',
            'created_at',
        ],
    ]) ?>
  </div>
</div>
