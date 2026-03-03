<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Filtered Results - ' . Html::encode($performance);
$this->params['breadcrumbs'][] = $this->title;
?>

 <div class="reports_page">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-12">
                <h2 class="heading"><?php echo Html::encode($performance); ?></h2>


                    <?php if (!empty($filteredAttempts)): ?>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <!-- <th>Student</th> -->
                                    <th>Lesson</th>
                                    <th>Points Earned</th>
                                    <th>Total Points</th>
                                    <th>Percentage</th>
                                    <th>Date</th>
                                    <th>Attempts</th>
                                </tr>
                            </thead>
                            <tbody>

                            <?php foreach ($filteredAttempts as $row): ?>
                            <?php
                                $lesson = $row['lesson'];
                                $attempt = $row['latest_attempt'];
                                $attemptCount = $row['attempts'];

                                // ✅ Use preloaded points instead of running DB query
                                $totalPoints = $row['total_points'] ?? 0;

                                $percentage = ($totalPoints > 0 && $attempt) ? ($attempt->score / $totalPoints) * 100 : 0;
                                $date = date('Y-m-d', $attempt->created_at);
                            ?>
                            <tr>
                                <td><?= Html::encode($lesson->title) ?></td>
                                 
                                <td><?= $attempt ? Html::encode($attempt->points_earned) : '-' ?></td>
                                 <td><?= Html::encode($totalPoints) ?></td>
                                <td><?= number_format($percentage, 2) ?>%</td>
                                <td><?= Html::encode($date) ?></td>
                                <td><?= $attemptCount ?></td>
                            </tr>
                        <?php endforeach; ?>



                            </tbody>
                        </table>
                        <?php else: ?>
                            <p>No test attempts found for performance level: <?= Html::encode($performance) ?>.</p>
                        <?php endif; ?>

                        <p>
                            <?= Html::a('Reports', Url::to(['reports/progress-report']), ['class' => 'theme_btn back']) ?>
                             
                        </p>

            </div>
        </div>
    </div>
</div>