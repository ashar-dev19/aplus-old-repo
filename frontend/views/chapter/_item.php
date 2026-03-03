<?php
/**
 * @var $this yii\web\View
 * @var $model common\models\Student
 * @var $chapterNumber integer // Passed from ListView
 */

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
$p = (int)($progress ?? 0);
?>
 

<a href="/lesson?id=<?= (int)$model->id ?>" class="box">
  <div class="inner">
    <div class="left">
      <div class="text"><h4>Chapter <?= (int)$chapterNumber ?></h4></div>
    </div>

    <div class="right">
      <div class="progress_bar">
        <div class="progress-done" data-done="<?= $p ?>">
          <?= $p > 0 ? $p.'%' : '0%' ?>
        </div>
      </div>

      <div class="text">
        <div class="content"><h4><?= Html::encode($model->title) ?></h4></div>
      </div>
    </div>
  </div>
</a>

<!-- box -->
