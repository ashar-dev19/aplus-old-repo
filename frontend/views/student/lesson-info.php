<?php
use yii\helpers\Html;
use yii\helpers\Url;
/** @var array $rows */
/** @var string $searchTitle */
$this->title = 'Found lessons for “ '.$searchTitle.' ”';
?>

<div class="reports_page">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-12">
                 <h3><?= Html::encode($this->title) ?></h3>
                 <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th>Lesson</th>
                            <th>Chapter</th>
                            <th>Grade</th>
                            <th>Open</th>
                          </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($rows as $r): ?>
                          <tr>
                            <td><?= Html::encode($r['lesson_title']) ?></td>
                            <td><?= Html::encode($r['chapter_title']) ?></td>
                            <td><?= Html::encode($r['grade_title']) ?></td>
                            <td>
                              <a class="btn btn-primary btn-sm"
                                href="<?= Url::to(['lesson-content/tutorial', 'lesson_id' => $r['lesson_id']]) ?>">
                                View
                              </a>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                        </tbody>
                      </table>
                 </div> 
                   

                    <a href="javascript:history.back()" class="btn btn-secondary">Back</a>

            </div>
      </div>        
  </div>
</div>     
 