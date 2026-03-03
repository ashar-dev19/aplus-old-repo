<?php

/**
 * @var yii\web\View $this
 * @var backend\modules\student_management\models\Student $model
 */

$this->title = 'Create Student';
$this->params['breadcrumbs'][] = ['label' => 'Students', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="student-create">

    <?php echo $this->render('_form', [
        'model' => $model,
        'grades' => $grades,
        'user_profiles' => $user_profiles
    ]) ?>

</div>
