<?php
/** 
 * @var $this yii\web\View
 * @var $model frontend\models\Lesson // Adjust the model type as per your application
 * @var $percentage mixed // Changed from int to mixed to allow "N/A"
 */

use yii\helpers\Html;

?>



<a href="/lesson-content/tutorial?lesson_id=<?= Html::encode($model->id) ?>" class="box">
    <div class="inner lesson_inner">
        <div class="left">
        <div class="text">
                <h4>Lesson <?= Html::encode($index) ?></h4> <!-- Displaying lesson number -->
            </div>
        </div>
        <div class="right">
            <div class="text">
                <div class="content">
                    <h4><?= Html::encode($model->title) ?></h4>
                </div>
                <div class="single-chart">
                    <svg viewBox="0 0 36 36" class="circular-chart green">
                        <path class="circle-bg"
                            d="M18 2.0845
                            a 15.9155 15.9155 0 0 1 0 31.831
                            a 15.9155 15.9155 0 0 1 0 -31.831"
                        />
                        <path class="circle"
                            stroke-dasharray="<?= is_numeric($percentage) ? $percentage : 0 ?>, 100"
                            d="M18 2.0845
                            a 15.9155 15.9155 0 0 1 0 31.831
                            a 15.9155 15.9155 0 0 1 0 -31.831"
                        />
                        <text x="18" y="20.35" class="percentage"><?= is_numeric($percentage) ? $percentage . '%' : $percentage ?></text>
                    </svg>
                    <p class="score">Score</p>
                </div>
            </div>
        </div>
    </div>
</a>

 
