<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Filtered Results - ' . Html::encode($performance);
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="filtered-results-page">
    <h1><?= Html::encode($this->title) ?></h1>
    
    <?php if (!empty($filteredAttempts)): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Lesson</th>
                    <th>Score</th>
                    <th>Total Points</th>
                    <th>Percentage</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($filteredAttempts as $attempt): 
                    $totalPoints = \frontend\models\LessonContent::find()
                        ->where(['lesson_id' => $attempt->lesson_test_id])
                        ->sum('points');
                    $percentage = ($totalPoints > 0) ? ($attempt->score / $totalPoints) * 100 : 0;
                    $studentName = $attempt->student->full_name;
                    $lessonTitle = $attempt->lesson->title;
                    $date = date('Y-m-d', $attempt->created_at);
                    ?>
                    <tr>
                        <td><?= Html::encode($studentName) ?></td>
                        <td><?= Html::encode($lessonTitle) ?></td>
                        <td><?= Html::encode($attempt->score) ?></td>
                        <td><?= Html::encode($totalPoints) ?></td>
                        <td><?= number_format($percentage, 2) ?>%</td>
                        <td><?= Html::encode($date) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No test attempts found for performance level: <?= Html::encode($performance) ?>.</p>
    <?php endif; ?>

    <p>
        <?= Html::a('Back to Reports', Url::to(['reports/progress-report']), ['class' => 'btn btn-primary']) ?>
    </p>
</div>
