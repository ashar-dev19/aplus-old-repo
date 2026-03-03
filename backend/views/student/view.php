<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\models\Notes;

$notesModel = new Notes();

/**
 * @var yii\web\View $this
 * @var backend\models\Student $model
 * @var yii\data\Pagination $pagination
 * @var array $testAttempts
 */

$this->title = $model->full_name;
$this->params['breadcrumbs'][] = ['label' => 'Students', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="student-view">
    <div class="card">
        <div class="card-header">
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        </div>
        <div class="card-body">
            <h3>Student Details</h3>
            <p><strong>Full Name:</strong> <?= Html::encode($model->full_name) ?></p>
            <p><strong>Email:</strong> <?= Html::encode($model->email) ?></p>
            <p><strong>Date of Birth:</strong> <?= Yii::$app->formatter->asDate($model->dob, 'php:Y-m-d') ?></p>

            <hr>
            <br>
            <h3>Progress Report</h3>
            
           <form id="reportFilter" method="get" action="">
    <input type="hidden" name="id" value="<?= Html::encode($model->id) ?>">
    <label for="date">Select Date</label>
    <input type="text"
           id="date"
           name="date"
           class="form-control"
           style="max-width:320px"
           placeholder="Select Date Range"
           value="<?= Html::encode($selectedDateRange ?? '') ?>"
           readonly>
    <button type="submit" class="btn btn-primary btn-sm mt-2">Show</button>
    <a href="<?= \yii\helpers\Url::to(['student/view','id'=>$model->id]) ?>"
       class="btn btn-secondary btn-sm mt-2">Clear</a>
</form>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
  (function(){
    const defaultRange = <?= json_encode(($selectedDateRange ?? '') ? explode(' to ', $selectedDateRange) : []) ?>;
    flatpickr("#date", {
      mode: "range",
      dateFormat: "Y-m-d",
      defaultDate: defaultRange,
      onChange: function(sel, str, inst){ inst.input.value = (sel.length===2)? str : ''; },
      
      onClose: function(sel){
        if (sel.length === 2) {
            var f = document.getElementById('reportFilter');
            if (f && f.requestSubmit) f.requestSubmit(); else if (f) f.submit();
        }
     }


    });
  })();
</script>


            <br>

     
            <div class="reports_table">
                <table class="table table-bordered">
  <thead>
    <tr>
      <th>Date</th>
      <th>Subject</th>
      <th>Chapter</th>
      <th>Lesson</th>
      <th>Attempts</th>
      <th>Points</th>
      <th>Percentage</th>
    </tr>
  </thead>
  <tbody> 

    <?php
     
    $subjectsById = \frontend\models\Subject::find()->indexBy('id')->all();

    $normalizeSubject = function (?int $sid) use ($subjectsById): string {
        if (!$sid) return 'N/A';
        if (in_array((int)$sid, [2, 5], true)) return 'English'; // Language Arts + English
        if ((int)$sid === 4) return 'Math';
        return $subjectsById[$sid]->title ?? 'N/A';
    };
    ?>

    <?php if (empty($testAttempts)): ?>

    
      <tr><td colspan="7" class="text-center">No records found for the selected date.</td></tr>
  <?php else: ?>
    <?php foreach ($testAttempts as $row): ?>
      <?php
        $lesson   = $row['lesson'];
        $latest   = $row['latest_attempt'];
        $attempts = (int)$row['attempts'];
        $total    = (int)$row['total_points'];

       
if ($lesson && $lesson->chapter && isset($lesson->chapter->subject_id)) {
    $sidRaw = (int)$lesson->chapter->subject_id;
} elseif ($lesson && isset($lesson->subject_id)) {
    $sidRaw = (int)$lesson->subject_id;
} elseif (isset($latest->subject_id)) {
    $sidRaw = (int)$latest->subject_id;
} else {
    $sidRaw = null;
}
$subject  = $normalizeSubject($sidRaw);
$chapter  = $lesson->chapter->title ?? '—';
$lessonT  = $lesson->title ?? '—';


         
        $points   = $latest->points_earned ?? $latest->score ?? 0;

        $pct      = $total > 0 ? min(100, round(($latest->score / $total) * 100, 2)) : 0;
        $dateStr  = Yii::$app->formatter->asDate($latest->created_at, 'php:Y-m-d');
      ?>
      <tr>
        <td><?= Html::encode($dateStr) ?></td>
        <td><?= Html::encode($subject) ?></td>
        <td><?= Html::encode($chapter) ?></td>
        <td><?= Html::encode($lessonT) ?></td>
        <td><?= Html::encode($attempts) ?></td>
        <td><?= Html::encode($points) ?></td>
        <td><?= Html::encode($pct) ?>%</td>
      </tr>
    <?php endforeach; ?>
  <?php endif; ?>
  </tbody>
</table>

            </div>
        </div>
    </div>

   

    <h3>Notes for <?= Html::encode($model->full_name) ?></h3>
    <?php if (!empty($model->notes)): ?>
        <table class="table table-bordered">
        <thead>
            <tr>
                <th>Note Title</th>
                <th>Note Content</th>
                <th>Note Status</th>
            </tr>
        </thead>
        <tbody>
        
            <?php foreach ($model->notes as $note): ?>
                <tr>
                    <td><?= Html::encode($note->title) ?></td>
                    <td> <?= Html::encode($note->body) ?></td>
                    <td> <?= $note->status ? 'Active' : 'Inactive' ?></td>
                 </tr>
                
            <?php endforeach; ?>
       </tbody>
    </table>

    <?php else: ?>
        <p>No notes available for this student.</p>
    <?php endif; ?>


</div>


           

 <br><br>

<h3>Add Note for Student</h3>

<?php
    
    $notesModel = new Notes();

 
    $form = ActiveForm::begin(['action' => ['student/view', 'id' => $model->id]]); 
?>

<?= $form->field($notesModel, 'student_id')->hiddenInput(['value' => $model->id])->label(false) ?>
<?= $form->field($notesModel, 'title')->textInput(['maxlength' => true]) ?>
<?= $form->field($notesModel, 'body')->textarea(['rows' => 6])->label("Note Content") ?>
<?= $form->field($notesModel, 'status')->dropDownList([1 => 'Active', 0 => 'Inactive'], ['prompt' => 'Select Status']) ?>

<div class="form-group">
    <?= Html::submitButton('Save Note', ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>


 



<?php
 
$this->registerJs("
    $('#date-picker').daterangepicker({
        autoApply: true,
        locale: {
            format: 'YYYY-MM-DD'
        }
    }).on('apply.daterangepicker', function(ev, picker) {
        const startDate = picker.startDate.format('YYYY-MM-DD');
        const endDate = picker.endDate.format('YYYY-MM-DD');
        window.location.href = '" . Url::to(['student/view', 'id' => $model->id]) . "&start_date=' + startDate + '&end_date=' + endDate;
    });
");
?>
