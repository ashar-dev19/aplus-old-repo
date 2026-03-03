<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

/* @var yii\web\View $this */
/* @var array $gradesWithChapters */
/* @var frontend\models\Subject $subject */
/* @var frontend\models\Student $student */
/* @var integer $id */

// Fetch the current student ID from the session
$currentStudent = Yii::$app->session->get('current_student');
$studentId = $currentStudent ? $currentStudent['id'] : null;

// Generate the dynamic Home URL
$homeUrl = $studentId ? Url::to(['/student/view', 'id' => $studentId]) : '#';

// Check if subject is set in the request
$subjectId = Yii::$app->request->get('subjectid');
$subject = $subjectId ? frontend\models\Subject::findOne($subjectId) : null;

// Set Breadcrumbs
$this->params['breadcrumbs'][] = ['label' => 'Home', 'url' => $homeUrl];
if ($subject) {
    $this->params['breadcrumbs'][] = ['label' => $subject->title];
}


?>
<!-- to remove/reset setup wizart -->
 <!-- localStorage.removeItem("gradeTutorialDisabled"); -->


<div class="course_page math">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-12">

                <?= Breadcrumbs::widget([
                    'homeLink' => false,
                    'links' => $this->params['breadcrumbs'],
                    'itemTemplate' => "<li class='breadcrumb-item'>{link}</li>\n",
                    'activeItemTemplate' => "<li class='breadcrumb-item active'>{link}</li>\n",
                    'options' => ['class' => 'breadcrumb'],
                ]) ?>

                <a href="<?= $homeUrl ?>" class="theme_btn back">
                    <i class="fa fa-chevron-left"></i> Home
                </a>
                 
                <h2 class="heading">Select Grade</h2>

                <!-- boxes -->
                <div class="boxes">
                    <div class="list-view">
                        <?php foreach ($gradesWithChapters as $item): ?>
                            <?php
                            $grade = $item['grade'];
                            $chapters = $item['chapters'];
                            ?>
                            <div class="col11">
                                <a href="/chapter?grade_id=<?= $grade->id ?>&subject_id=<?= $subject->id ?>" class="box cat-<?= $grade->id ?>">
                                    <div class="inner">
                                        <h3><?= Html::encode(preg_replace('/^[A-Z]-\s*/', '', $grade->title)) ?></h3>
                                        <p><?= Html::encode($grade->description) ?></p>
                                        <div class="skils">
                                            <div class="chapters">
                                                <div class="sk_title"><h4>Chapters Available</h4></div>
                                                <div class="sk_link"><span><?= count($chapters) ?></span></div>
                                            </div>

                                            <!-- <?php //if ($grade->live_class_link): ?>
                                                <div class="classes_link" data-url="<?php //echo Html::encode($grade->live_class_link) ?>">
                                                    <div class="sk_title"><h4>Live Classes</h4></div>
                                                    <div class="sk_link"><span><?php //echo Html::encode($grade->live_class_day . ' ' . $grade->live_class_time) ?></span></div>
                                                </div>
                                            <?php //else: ?>
                                                <div class="classes_link no-link">
                                                    <div class="sk_title"><h4>Live Classes</h4></div>
                                                    <div class="sk_link"><span>M & W&nbsp;5-5:45</span></div>
                                                </div>
                                            <?php //endif; ?> -->

                                            <?php
                                                $STATIC_LIVE_URL = 'https://m.youtube.com/@aplusclasses1756/streams';
                                                $liveLabel = 'Visit';
                                                 
                                                ?>
                                                <div class="classes_link" data-url="<?= Html::encode($STATIC_LIVE_URL) ?>">
                                                    <div class="sk_title"><h4>Live Classes</h4></div>
                                                    <div class="sk_link"><span><?= Html::encode($liveLabel) ?></span></div>
                                                </div>

                                            <div class="ribbon prek"><span><?= Html::encode(substr($grade->title, 0, 2)) ?></span></div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div> <!-- boxes -->

                      <!-- custom overlay -->
        <div id="gradeTutorialOverlay" class="tutorial-overlay"></div>

            </div>
        </div>
    </div>
</div>

<!-- Intro.js Styles & Script -->
<!-- <link href="https://unpkg.com/intro.js/minified/introjs.min.css" rel="stylesheet">
<script src="https://unpkg.com/intro.js/minified/intro.min.js"></script> -->
 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.1.0/introjs.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.1.0/intro.min.js"></script>


<!-- tool tip script -->
<!-- <script>
  document.addEventListener("DOMContentLoaded", function () {
  // bail if user clicked “Do not show again” before
  if (localStorage.getItem("gradeTutorialDisabled")) return;

  // show custom overlay
  document.getElementById("gradeTutorialOverlay").style.display = "block";

  const intro = introJs();
  intro.setOptions({
        steps: [
            {
            element: ".boxes .box",
            intro: "Select a grade by clicking on the grade box.",
            position: "bottom",
            showButtons: true // ✅ show default buttons
            },
            {
            element: ".classes_link",
            intro: "Click here to join live classes.",
            position: "bottom",
            showButtons: true  
            },
            {
            intro: "Setup Complete!",
            position: "bottom",
            tooltipClass: "introjs-custom-final",
            showButtons: false, 
            disableInteraction: true
            }
        ],
        showStepNumbers: false,
        showBullets: false,
        overlayOpacity: 0,
        exitOnOverlayClick: true,
        showSkipButton: false,
        nextLabel: "Next",
        prevLabel: "Back"
  });
    
   intro.onafterchange(() => {
            const step = intro._currentStep;
            const tooltip = document.querySelector('.introjs-tooltip');
            if (!tooltip) return;

            // ensure tooltip visible
            tooltip.style.display = 'block';
            tooltip.style.zIndex = '10001';

          if (step === 2) {
        // hide default buttons (Back/Done)
        const prevBtn = tooltip.querySelector('.introjs-prevbutton');
        const doneBtn = tooltip.querySelector('.introjs-donebutton');

        if (prevBtn) prevBtn.style.display = 'none';
        if (doneBtn) doneBtn.style.display = 'none';

        // inject custom buttons
        const footer = tooltip.querySelector('.introjs-tooltipbuttons');
        footer.innerHTML = `
            <button id="doNotShowBtn" class="introjs-button custom-gray">Do not show again</button>
            <button id="remindLaterBtn" class="introjs-button">Remind me later</button>
        `;

        // attach click handlers
        document.getElementById('doNotShowBtn').onclick = () => {
            localStorage.setItem('gradeTutorialDisabled', 'true');
            intro.exit();
        };

        document.getElementById('remindLaterBtn').onclick = () => {
            intro.exit();
        };
    }

        });


    intro.onexit(() => {
    document.getElementById("gradeTutorialOverlay").style.display = "none";
  });

  intro.start();
});
</script> -->



<script>
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".classes_link").forEach(function(classesLink) {

        classesLink.addEventListener("click", function(event) {
            event.preventDefault(); 
            event.stopPropagation();

            let customUrl = this.getAttribute("data-url");
            if (customUrl) {
                console.log("Opening PDF:", customUrl); 
                window.open(customUrl, "_blank");
            }
        });

    });

    document.querySelectorAll(".classes_link").forEach(function(classesLink) {
        classesLink.addEventListener("click", function(event) {
            event.stopPropagation();
        });
    });

});
</script>


<style>
.tutorial-overlay { position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); display:none; z-index:10000; }
.introjs-helperLayer { box-shadow:0 0 0 3px yellow !important; border-radius:5px; }
.introjs-custom-final { font-style:italic; text-align:center; padding:15px; font-size:16px; }
.introjs-button {
     background:#1EB2A6!important; color:#fff!important; border:none!important; padding:6px 12px!important; cursor:pointer!important; 
    text-shadow:none;
}
.introjs-button:hover { background:#17a093!important; }
.custom-gray { background:#ddd!important; color:#000!important; margin-right:10px!important; }
.custom-gray:hover{
    color:#fff !important;
}
 
/* Hide Intro.js default buttons on final step */
.introjs-tooltip.introjs-custom-final .introjs-prevbutton,
.introjs-tooltip.introjs-custom-final .introjs-nextbutton,
.introjs-tooltip.introjs-custom-final .introjs-donebutton {
    display: none !important;
}
 

</style>
