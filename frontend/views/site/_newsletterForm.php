<?php
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/** @var \frontend\models\Newsletter $model */

$form = ActiveForm::begin([
    'action' => ['site/subscribe'],
    'method' => 'post',
    'options' => ['class' => 'newsletter-form'],
    'enableClientValidation' => true,
]);
?>

<?= $form->field($model, 'email')->input('email', [
    'placeholder' => 'Enter your email',
    'required'    => true,
    'maxlength'   => 222,
    'pattern'     => '^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[A-Za-z]{2,}$',
])->label(false) ?>

<!-- honeypot -->
<input type="text" name="hp" value="" style="display:none" autocomplete="off">
<!-- time-to-submit -->
<input type="hidden" name="t0" value="<?= time() ?>">

<?= Html::submitButton('Subscribe <i class="fa fa-paper-plane"></i>', ['class' => 'btn btn-primary']) ?>

<?php ActiveForm::end(); ?>


 <?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success">
        <?= Yii::$app->session->getFlash('success') ?>
    </div>
    <?php endif; ?>

    <?php if (Yii::$app->session->hasFlash('error')): ?>
    <div class="alert alert-danger">
        <?= Yii::$app->session->getFlash('error') ?>
    </div>
<?php endif; ?>


<?php
$this->registerJs(<<<JS
  $(document).on('submit', '.newsletter-form', function(e){
     var email = $(this).find('input[type=email]').val().trim();
     if (!email) { alert('Please enter an email.'); e.preventDefault(); return false; }
     $(this).find('button[type=submit]').prop('disabled', true);
  });
JS);

