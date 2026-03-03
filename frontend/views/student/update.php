<?php

/**
 * @var yii\web\View $this
 * @var backend\modules\student_management\models\Student $model
 */

$this->title = 'Update Student: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Students', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
 


<div class="student_update">
    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
        <h2 class="heading">Manage Profile</h2>
            <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>


        </div>
    </div>

    </div>
</div>
