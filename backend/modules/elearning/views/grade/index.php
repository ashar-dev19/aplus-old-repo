
<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\elearning\models\search\GradeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Grades');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="grade-index">
    <div class="card">
        <div class="card-header">
            <?php //echo Html::a(Yii::t('backend', 'Create {modelClass}', ['modelClass' => 'Grade']), ['create'], ['class' => 'btn btn-success']) ?>
        </div>

        <div class="card-body">
           

            <!-- Subject Dropdown -->
            <label>Select Subject:</label>
            <?php //echo  Html::dropDownList('subject', $selectedSubject, $subjects, [
                //'class' => 'form-control',
                //'prompt' => 'Select Subject',
                //'onchange' => 'window.location.href = "' . Url::to(['grade/index']) . '?subjectid=" + this.value',
            //]); ?>

            <?= Html::dropDownList(
                'subject', 
                $selectedSubject, 
                \yii\helpers\ArrayHelper::map($subjects, 'id', 'title'), // ID value, Title display
                [
                    'class' => 'form-control',
                    'prompt' => 'Select Subject',
                    'onchange' => 'window.location.href = "' . Url::to(['grade/index']) . '?subjectid=" + this.value',
                ]
            ); ?>


            

            
            <br>
          

            <?= GridView::widget([
                'layout' => "{items}\n{pager}",
                'options' => [
                    'class' => ['gridview', 'table-responsive'],
                ],
                'tableOptions' => [
                    'class' => ['table', 'text-nowrap', 'table-striped', 'table-bordered', 'mb-0'],
                ],
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    // 'id',
                    'title',
                    // 'description:ntext',

                    ['class' => \common\widgets\ActionColumn::class],
                ],
            ]); ?>

 

            
        </div>
    </div>
</div>

<div class="card-footer">
            <?php getDataProviderSummary($dataProvider) ?>
</div>
