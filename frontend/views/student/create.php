<?php



/**

 * @var yii\web\View $this

 

 */



$this->title = 'Create Student';

$this->params['breadcrumbs'][] = ['label' => 'Students', 'url' => ['index']];

$this->params['breadcrumbs'][] = $this->title;

?>

<div class="student_create">

<div class="container">

    <?php echo $this->render('_form', [

        'model' => $model,
 ]) ?>

</div>

</div>

