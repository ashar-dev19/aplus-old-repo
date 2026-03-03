<?php
use yii\helpers\Html;

/** @var \common\models\TimelineEvent $model */

// safe decode
if (is_array($model->data)) {
    $d = $model->data;
} else {
    $tmp = is_string($model->data) ? json_decode($model->data, true) : [];
    $d = is_array($tmp) ? $tmp : [];
}

// text pieces
$name  = $d['name']  ?? 'Someone';
$phone = $d['phone'] ?? null;

$url = ['/assessment/view', 'id' => $d['assessment_id'] ?? 0];
?>
<i class="bg-green fas fa-clock"></i>
 
<p class="timline_createdat"><?= Yii::$app->formatter->asRelativeTime($model->created_at) ?></p>

<h3 class="timeline-header">
  New assessment booked by <b><?= Html::encode($name) ?></b>
  <?php if ($phone): ?> (<?= Html::encode($phone) ?>)<?php endif; ?>
</h3>

<div class="timeline-body">
  <?= Html::a('Open assessment', $url, ['class' => 'timeline_open_btn']) ?>
</div>
