<?php

/**
 * @var $this yii\web\View
 * @var $model common\models\Student
 * @var $gradesWithChapters array // Assuming this is passed from the controller
 */

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\StringHelper;

?>

<?php
// This part assumes you have access to the grade title and model data from the controller.
$grade_title = $model->title;
$firstCharacter = substr($grade_title, 0, 2);
?>

<?php foreach ($gradesWithChapters as $gradeData): ?>
    <div class="box">
        <div class="inner">
            <!-- Grade Title and Description -->
            <a href="/chapter?grade_id=<?= $gradeData['grade']->id ?>" class="title">
                <h2><?= Html::encode($gradeData['grade']->title) ?></h2>
                <p><?= Html::encode($gradeData['grade']->description) ?></p>
            </a>

            <div class="skils">
                <!-- Lessons Available -->
                <div class="sk_title">
                    <h4>Lessons Available</h4>
                </div>
                <div class="sk_link">
                    <a href="#"><?= count($gradeData['chapters']) ?></a> <!-- Display total chapters as lessons available -->
                </div>

                <!-- Chapters in Progress -->
                <div class="sk_title">
                    <h4>In Progress</h4>
                </div>
                <div class="sk_link">
                    <a href="#"><?= count($gradeData['chapters_in_progress']) ?></a> <!-- Display chapters in progress -->
                </div>

                <!-- Finished Chapters -->
                <div class="sk_title">
                    <h4>Finished</h4>
                </div>
                <div class="sk_link">
                    <a href="#">
                        <?php
                        // Assuming "Finished" chapters means chapters with all lessons completed
                        $finishedChapters = count($gradeData['chapters']) - count($gradeData['chapters_in_progress']);
                        echo $finishedChapters;
                        ?>
                    </a>
                </div>

                <!-- Live Classes Placeholder -->
                <div class="sk_title">
                    <h4>Live Classes</h4>
                </div>
                <div class="sk_link">
                    <a href="#">M & W 5-5:45</a> <!-- Hardcoded, change if necessary -->
                </div>

                <!-- Ribbon with the first two characters of the grade -->
                <div class="ribbon prek"><span><?= $firstCharacter; ?></span></div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
