<?php

/**
 * @var yii\web\View $this
 * @var backend\modules\elearning\models\Chapter $model
 */

$this->title = 'Create Chapter';
$this->params['breadcrumbs'][] = ['label' => 'Chapters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="chapter-create">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
