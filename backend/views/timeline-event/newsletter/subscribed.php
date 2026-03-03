<?php
use yii\helpers\Html;

/** @var $this yii\web\View */
/** @var $model common\models\TimelineEvent */

// data can be JSON string or array:
$d = is_array($model->data) ? $model->data : (json_decode($model->data, true) ?: []);
$email = $d['email'] ?? '(unknown)';

// where to link:
$url = ['/newsletter/index'];   
?>
<span class="time"><i class="fa fa-clock-o"></i> <?= Yii::$app->formatter->asRelativeTime($model->created_at) ?></span>
<h3 class="timeline-header">
  New newsletter signup by <b><?= Html::encode($email) ?></b>
</h3>
<div class="timeline-body">
  <?= Html::a('Open newsletter list', $url, ['class'=>'btn btn-sm btn-primary']) ?>
</div>
