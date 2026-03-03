<?php
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use frontend\models\Grade;

/** @var $this yii\web\View */
/** @var $model backend\models\Student */

$parentText = '';
if ($model->parent_id) {
    $p = \webvimark\modules\UserManagement\models\User::findOne($model->parent_id);
    if ($p) {
        $parentText = $p->username . ' (' . ($p->email ?: 'no-email') . ')';
    }
}
?>

<div class="student-form">
<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
  <div class="card">
    <div class="card-body">
      <?= $form->errorSummary($model) ?>

      <!-- Parent (Select2 + AJAX) -->
      <?= $form->field($model, 'parent_id')
          ->dropDownList(
              $model->parent_id ? [$model->parent_id => $parentText] : [],
              ['id' => 'parent-id', 'prompt' => 'Search parent...']
          )
          ->label('Parent Name') ?>

      <?= $form->field($model, 'full_name')->textInput(['maxlength' => true]) ?>
      <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

     
        <?= $form->field($model, 'details')->fileInput() ?>

      <?php //echo  $form->field($model, 'grade_id')->dropDownList(
           // ArrayHelper::map(Grade::find()->all(), 'id', 'title'),
           // ['prompt' => 'Select Grade']
          //)->label('Grade') ?>

      <?= $form->field($model, 'gender')->dropDownList(
            [1 => 'Male', 2 => 'Female', 0 => 'Other'],
            ['prompt' => 'Select Gender']
          ) ?>

      <?php
      $dobValue = ($model->dob && $model->dob !== '0000-00-00') ? $model->dob : null;
      echo $form->field($model, 'dob')->input('date', [
          'max'   => date('Y-m-d'),
          'value' => $dobValue,
      ]);
      ?>

      <?= $form->field($model, 'status')->dropDownList(
            [1 => 'Active', 0 => 'Inactive'],
            ['prompt' => 'Select Status']
          ) ?>
    </div>

    <div class="card-footer">
      <?= Html::submitButton(
            $model->isNewRecord ? 'Create' : 'Update',
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
          ) ?>
    </div>
  </div>
<?php ActiveForm::end(); ?>
</div>

<?php
// Assets (use FULL build so AJAX works)
$this->registerCssFile(
  'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
  ['depends' => [\yii\web\JqueryAsset::class]]
);
$this->registerJsFile(
  'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.min.js',
  ['depends' => [\yii\web\JqueryAsset::class]]
);

// Init Select2 (works after PJAX too)
$searchUrl = Url::to(['/user-management/user/parent-search']);
$js = <<<JS
function initParentSelect(){
  var \$el = $('#parent-id');
  if (!\$el.data('select2')) {
    \$el.select2({
      placeholder: 'Search parent by name or email',
      allowClear: true,
      minimumInputLength: 1,
      width: '100%',
      ajax: {
        url: '{$searchUrl}',
        dataType: 'json',
        delay: 250,
        data: function (params) { return { q: params.term || '' }; },
        processResults: function (data) { return data && data.results ? data : {results:[]}; },
        cache: true
      }
    });
  }
}
initParentSelect();
$(document).on('pjax:end', initParentSelect);
JS;
$this->registerJs($js);
?>
