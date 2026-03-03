<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var \backend\models\Student         $student */
/** @var \frontend\models\Points|null    $pointsRow */
/** @var int                             $totalPoints */
?>

<h1>Add Points for <?= Html::encode($student->full_name) ?></h1>

<div class="card" style="max-width:560px;">
  <div class="card-body">
    <p><b>Total Points:</b> <?= Html::encode($totalPoints) ?></p>

    <?php if ($pointsRow): ?>
      <p>
        <b>Last Updated By:</b>
        <?php
          $addedBy = $pointsRow->creator->full_name
                    ?? $pointsRow->creator->username
                    ?? 'System';
          echo Html::encode($addedBy);
        ?>
      </p>
    <?php endif; ?>

   <?php $form = ActiveForm::begin(); ?>

<div class="mb-3">
  <label class="form-label">Add Points</label>
  <input type="number" name="add_points" min="1" step="1" class="form-control" placeholder="e.g. 50">
</div>

<div class="mb-3">
  <label class="form-label">Deduct Points</label>
  <input type="number" name="deduct_points" min="1" step="1" class="form-control" placeholder="e.g. 10">
</div>

<div class="mb-3">
  <label class="form-label">Details (optional)</label>
  <input type="text" name="details" class="form-control" placeholder="Reason / note">
</div>

<button type="submit" class="btn btn-primary">Save</button>
<?php ActiveForm::end(); ?>


  </div>
</div>
