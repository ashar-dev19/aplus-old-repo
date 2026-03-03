<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Quiz Result';
$this->params['breadcrumbs'][] = $this->title;
?>

<link href="https://unpkg.com/intro.js/minified/introjs.min.css" rel="stylesheet">
<script src="https://unpkg.com/intro.js/minified/intro.min.js"></script>

<div class="results_page">

     


    <?php if (isset($totalMarks, $obtainedMarks, $percentage)): ?>
    <div class="container">
        <div class="quiz_heading">
            <div class="inner">
                <div class="">
                    <h1 class="heading">Test Result</h1>
                    <span><i class="fa fa fa-check"></i></span>
                    <!-- <h3>Nice Job. <span class="student_name"><?php //echo Html::encode($studentName) ?></span> You Passed.</h3>
                    <h4>Date Completed: <span><?php //Html::encode(Yii::$app->formatter->asDate(date('Y-m-d'), 'php:F jS, Y')) ?> </span> </h4> -->

                    

                    <?php if ($percentage >= 80): ?>
                        <h3>Nice Job <span class="student_name"><?= Html::encode($studentName) ?>!</span> You Passed.</h3>
                        <?php else: ?>
                        <h3>Good effort <span class="student_name"><?= Html::encode($studentName) ?></span>! Keep trying and you'll improve!</h3>
                    <?php endif; ?>
                    <h4>Date Completed: <span><?= Html::encode(Yii::$app->formatter->asDate(date('Y-m-d'), 'php:F jS, Y')) ?> </span> </h4>
 

                    <?php
                        $chapter_id = Yii::$app->db->createCommand("SELECT chapter_id FROM lesson_content WHERE lesson_id = :lesson_id")
                            ->bindValue(':lesson_id', $lesson_id)
                            ->queryScalar();
                    ?>

                    <a href="<?= \yii\helpers\Url::to(['lesson/index', 'id' => $chapter_id]) ?>" class="theme_btn back">
                        <i class="fa fa-arrow-left"></i> Lessons
                    </a>



                     

                </div>
            </div>
        </div>

        <div class="quiz_boxes">
            <div class="inner">
                <div class="box ">
                    <h4>Your Result</h4>
                    <h2><?= Html::encode($percentage) ?>%</h2>
                    <hr class="divider">
                    <p>Passing Score 80%</p>
                     
                </div>
                <div class="box reward-box">
                    <h4>You Earned</h4>
                    <h2><?= Html::encode($obtainedMarks) ?></h2>
                    <hr class="divider">
                    <p>Reward Points</p>
                    <p style="display:none;">Reward <?= Html::encode($totalMarks) ?> Points</p>
                </div>
            </div>

            <div class="inner">
                <div class="box lesson_box">
                    <h4>Lesson Name</h4>
                    <h2><?= Html::encode($lessonName) ?></h2>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="alert alert-danger">
        <p>No test score found for the specified lesson and student.</p>
    </div>
    <?php endif; ?>
</div>

 

<style>
.introjs-custom-final .introjs-tooltipbuttons {
  display: none !important;
}

.introjs-button {
  background-color: #1EB2A6 !important;
  color: white !important;
  border: none !important;
  padding: 6px 12px !important;
  font-size: 14px;
  cursor: pointer !important;
  border-radius: 4px !important;
  box-shadow: none !important;
}
.introjs-button:hover {
  background-color: #17a093 !important;
}
.custom-gray {
  background: #ddd !important;
  color: #000 !important;
  margin-right: 10px !important;
}

.setup-final {
  text-align: center;
}
.setup-final .final-message {
  font-size: 15px;
  font-weight: bold;
  margin-bottom: 10px;
}
.setup-final .final-buttons {
  display: flex;
  justify-content: center;
  gap: 10px;
}
</style>

<!-- <script>
document.addEventListener("DOMContentLoaded", function () {
    if (localStorage.getItem("wizardDismissedResult")) return;

    const intro = introJs();
    intro.setOptions({
        steps: [
            {
                element: document.querySelector(".reward-box"),
                intro: `
                  <div class="setup-final">
                    <div class="final-message">Must score over 80% to get:
                      <br>100 points for 1st-3rd attempts
                      <br>50 points for 4th-5th
                      <br>10 points after that
                    </div>
                    <div class="final-buttons">
                      <button id="doNotShowBtn" class="introjs-button custom-gray">Do not show again</button>
                      <button id="remindLaterBtn" class="introjs-button">Remind me later</button>
                    </div>
                  </div>
                `,
                position: "bottom",
                tooltipClass: "introjs-custom-final",
                disableInteraction: true
            }
        ],
        showStepNumbers: false,
        showBullets: false,
        overlayOpacity: 0.6,
        exitOnOverlayClick: true,
        showSkipButton: false,
        nextLabel: "Next",
        prevLabel: "Back"
    });

    intro.onafterchange(() => {
        setTimeout(() => {
            document.getElementById("doNotShowBtn")?.addEventListener("click", () => {
                localStorage.setItem("wizardDismissedResult", "true");
                intro.exit();
            });
            document.getElementById("remindLaterBtn")?.addEventListener("click", () => {
                intro.exit();
            });
        }, 0);
    });

    setTimeout(() => {
        if (document.querySelector(".reward-box")) {
            intro.start();
        }
    }, 500);
});
</script> -->



<script>
  // Reset the tutorial button press count and saved slide index for a new attempt
  localStorage.removeItem("tutorialPressCount_<?= $lesson_id ?>");
  localStorage.removeItem("lastVisitedSlide_<?= $lesson_id ?>");
  // Optionally, clear the quiz completed flag if you are using it:
  localStorage.removeItem("quizCompleted_<?= $lesson_id ?>");
  console.log("Tutorial press limit and saved slide index reset for lesson <?= $lesson_id ?>");
</script>
