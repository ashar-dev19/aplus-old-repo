<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/** @var $this yii\web\View */
/** @var $model backend\models\Student */

$this->title = 'Add Student';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// Preselected text (edit case)
$parentText = '';
if ($model->parent_id) {
    $p = \webvimark\modules\UserManagement\models\User::findOne($model->parent_id);
    if ($p) {
        $parentText = $p->username . ' (' . ($p->email ?: 'no-email') . ')';
    }
}
?>

<div class="student-form">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
     <?= $form->field($model, 'parent_id')
        ->dropDownList(
            $model->parent_id ? [$model->parent_id => $parentText] : [],
            ['id' => 'parent-id', 'prompt' => 'Search parent...']
        )
        ->label('Parent Name'); ?>


    <?= $form->field($model, 'full_name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

   

   

    <?= $form->field($model, 'details')->fileInput() ?>
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

    <?= $form->field($model, 'status')->dropDownList([1 => 'Active', 0 => 'Inactive']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
// ---- Load assets the Yii way (ensures jQuery dependency) ----
$this->registerCssFile(
    'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
    ['depends' => [\yii\web\JqueryAsset::class]]
);
// Use FULL build (AJAX support)
$this->registerJsFile(
    'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.min.js',
    ['depends' => [\yii\web\JqueryAsset::class]]
);

// ---- Initialize (also after PJAX, if any) ----
$searchUrl = Url::to(['/user-management/user/parent-search']);
$js = <<<JS
function initParentSelect() {
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
