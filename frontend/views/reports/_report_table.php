<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;


?>

<table border="1">
    <thead>
        <tr>
            <th>Date</th>
            <th>Subject</th>
            <th>Chapter</th>
            <th>Lesson</th>
            <th>Points</th>
            <th>Percentage</th>
            <th>Questions</th>
            <th>Start Date</th>
            <th>Finish Date</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($testAttempts as $item): ?>
            <?php
            $attempt = $item['attempt'];
            $totalQuestions = $item['totalQuestions'];
            $totalPoints = LessonContent::find()->where(['lesson_id' => $attempt->lesson_test_id])->sum('points');
            $scorePercentage = ($totalPoints > 0) ? ($attempt->score / $totalPoints) * 100 : 0;
            ?>
            <tr>
                <td><?= Html::encode($attempt->created_at) ?></td>
                <td><?= Html::encode($attempt->lesson->subject->title) ?></td>
                <td><?= Html::encode($attempt->lesson->chapter->title) ?></td>
                <td><?= Html::encode($attempt->lesson->title) ?></td>
                <td><?= Html::encode($attempt->score) ?></td>
                <td><?= Html::encode(round($scorePercentage, 2)) ?>%</td>
                <td><?= Html::encode($totalQuestions) ?></td>
                <td><?= Html::encode($attempt->start_date) ?></td>
                <td><?= Html::encode($attempt->finish_date) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Render pagination controls -->
<nav id="w0">
    <?= LinkPager::widget(['pagination' => $pagination]) ?>
</nav>
