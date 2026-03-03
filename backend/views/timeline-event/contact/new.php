<?php
/** @var $model \common\models\TimelineEvent */
use yii\helpers\Html;

// $model->data can be array or JSON:
$d = is_array($model->data) ? $model->data : (json_decode($model->data, true) ?: []);

$id    = (int)($d['contact_id'] ?? 0);
$name  = trim((string)($d['name']   ?? 'Someone'));
$email = trim((string)($d['email']  ?? ''));
$subj  = trim((string)($d['subject'] ?? ''));

// Backend detail link jo SiteController ne data me inject kiya tha:
 
$link = ['/contact-queries/view', 'id' => $id]; 

// // Fallback agar 'link' missing ho:
// if ($link === '') {
//     $link = ['/contact-queries/view', 'id' => $id];
// }
?>
<span class="time"><i class="fa fa-clock-o"></i> <?= Yii::$app->formatter->asRelativeTime($model->created_at) ?></span>

<h3 class="timeline-header">
  New contact query by <b><?= Html::encode($name) ?></b>
  <?php if ($email): ?>
    (<?= Html::encode($email) ?>)
  <?php endif; ?>
</h3>

<div class="timeline-body">
  <?php if ($subj): ?>
    <p><b>Subject:</b> <?= Html::encode($subj) ?></p>
  <?php endif; ?>

  <?= Html::a('Open contact', $link, ['class' => 'btn btn-sm btn-primary']) ?>
</div>
