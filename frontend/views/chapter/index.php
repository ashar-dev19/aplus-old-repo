<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use frontend\models\Subject;


/* @var $this yii\web\View */
/* @var $searchModel frontend\models\search\ChapterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Chapter';
// $this->params['breadcrumbs'][] = $this->title;

// Fetch the current student ID from the session
$currentStudent = Yii::$app->session->get('current_student');
$studentId = $currentStudent ? $currentStudent['id'] : null;


$subjectId = Yii::$app->request->get('subject_id'); 
$subjectName = '';
if ($subjectId) {
    $subject = Subject::findOne($subjectId);
    if ($subject) {
        $subjectName = $subject->title; 
    }
}

// Generate the dynamic Home URL
$homeUrl = $studentId ? Url::to(['/student/view', 'id' => $studentId]) : '#';



// Set dynamic subject URL
$subjectUrl = Url::to(['/subject/view', 'id' => $subjectId]);

// Set dynamic grade URL
$gradeUrl = Url::to(['/grade/grades', 'id' => $gradeId, 'subjectid' => $subjectId]);

// Set Breadcrumbs
$this->params['breadcrumbs'][] = ['label' => 'Home', 'url' => $homeUrl];

if (!empty($subjectName)) {
    $this->params['breadcrumbs'][] = ['label' => $subjectName, 'url' => $gradeUrl];
}

if (!empty($gradeName)) {
    $this->params['breadcrumbs'][] = ['label' => $gradeName];
}

/* @var array $chaptersProgress */


 

?>

<div class="topic_page chapters_page">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-12">
            
            <!-- Breadcrumbs Widget -->
            <?= Breadcrumbs::widget([
                'homeLink' => false,
                'links' => $this->params['breadcrumbs'],
                'itemTemplate' => "<li class='breadcrumb-item'>{link}</li>\n",
                'activeItemTemplate' => "<li class='breadcrumb-item active'>{link}</li>\n",
                'options' => ['class' => 'breadcrumb'],
            ]) ?>

                <!-- <h2 class="heading">Chapters</h2> -->
                
                <a href="<?= Url::to(['/grade/grades', 'id' => $gradeId, 'subjectid' => $subjectId]) ?>" class="theme_btn back" >
                    <i class="fa fa-chevron-left"></i><?= Html::encode($gradeName) ?>
                </a>

                

               <?php //if ($studentId !== null) {
                    //echo "Current Student ID: " . $studentId; // For debugging
                //}?> 
                 <!-- boxes -->
                <div class="boxes">
                    <?php
                    $chapterCounter = 0;  
                    ?>
                    <?= \yii\widgets\ListView::widget([
                        'dataProvider' => $dataProvider,
                        'pager' => ['hideOnSinglePage' => true],
                        'itemView' => function ($model, $key, $index, $widget) use (&$chapterCounter, $chaptersProgress) {
                            $chapterCounter++;
                            return $this->render('_item', [
                                'model'         => $model,
                                'chapterNumber' => $chapterCounter,
                                'progress'      => $chaptersProgress[$model->id] ?? 0,  
                            ]);
                        },
                        'layout' => '{items}',
                        ]) ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="btns">
                </div>
            </div>
        </div>

         <div id="gradeTutorialOverlay"></div>
        <!-- <div id="chapterTutorialOverlay" class="tutorial-overlay">
            <div id="chapterTutorialBox" class="tutorial-box">
                <p id="chapterTutorialText"></p>
                <div class="wiz_buttons">
                    <button id="chapterPrevBtn" style="display: none;">Previous</button>
                    <button id="chapterNextBtn" class="next">Next</button>
                </div>
                <div class="wiz_buttons">
                    <button id="chapterDisableTutorialBtn" style="display: none;">Do not show again</button>
                    <button id="chapterRemindLaterBtn" style="display: none;" class="next">Remind me later</button>
                </div>
            </div>
        </div> -->
                   

    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.1.0/introjs.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.1.0/intro.min.js"></script>

<!-- tool tip script -->
<!-- <script>
document.addEventListener("DOMContentLoaded", function () {
  // stop if user already dismissed this tutorial
  if (localStorage.getItem("chapterTutorialDisabled")) return;

  const overlay = document.getElementById("gradeTutorialOverlay");
  if (overlay) overlay.style.display = "block";

  const intro = introJs();
  intro.setOptions({
    steps: [
      {
        element: document.querySelector(".boxes .box"),
        intro: "Select a chapter to begin the lesson.",
        position: "bottom" 
      },
      { 
        element: document.querySelector(".progress-done"),
        intro: "Progress will be shown inside the progress bar.",
        position: "bottom" 
      },
      // last step (no element)
      { 
        intro: "Setup Complete!", position: "bottom", disableInteraction: true

       }
    ],
    showButtons: true,           // keep default buttons for non-final steps
    showStepNumbers: false,
    showBullets: false,
    overlayOpacity: 0.6,
    exitOnOverlayClick: true,
    showSkipButton: false,
    nextLabel: "Next",
    prevLabel: "Back"
  });

  function renderLastStepButtons() {
    const isLast = intro._currentStep === intro._introItems.length - 1;
    const footer = document.querySelector(".introjs-tooltipbuttons");
    if (!footer) return;

    if (isLast) {
      // wipe default prev/next/done and add our two buttons
      footer.innerHTML = "";

      const dont = document.createElement("button");
      dont.className = "introjs-button custom-gray";
      dont.textContent = "Don't show again";
      dont.onclick = function () {
        localStorage.setItem("chapterTutorialDisabled", "1");
        intro.exit();
      };

      const ok = document.createElement("button");
      ok.className = "introjs-button";
      ok.textContent = "OK, got it";
      ok.onclick = function () { intro.exit(); };

      footer.appendChild(dont);
      footer.appendChild(ok);
    }
  }

  intro.onafterchange(renderLastStepButtons);
  intro.onchange(renderLastStepButtons);

  intro.onexit(function () {
    if (overlay) overlay.style.display = "none";
  });

  // start only if the first-step target exists
  if (document.querySelector(".boxes .box")) {
    intro.start();
  } else if (overlay) {
    overlay.style.display = "none";
  }
});
</script> -->




<style>
.introjs-helperLayer {
    border-radius: 5px;
    box-shadow: 0 0 0 3px yellow !important;
}
.introjs-tooltiptext {
    font-size: 14px;
}
.introjs-button { background:#1EB2A6!important; color:#fff!important; border:none!important; padding:6px 12px!important; cursor:pointer!important; 
text-shadow:none;
}
.introjs-button:hover { background:#17a093!important; }
.custom-gray {
    background: #ddd !important;
    color: #000 !important;
    border: none;
    margin-right: 10px;
    padding: 6px 12px;
    border-radius: 4px;
    font-size: 14px;
    cursor: pointer;
}
.custom-gray:hover{
    color:#fff !important;
}
#gradeTutorialOverlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.6);
    display: none;
    z-index: 9998;
}
.introjs-custom-final {
    text-align: center;
    font-style: italic;
    font-size: 16px;
    padding: 15px;
}
</style>


<!-- <script>
    document.addEventListener("DOMContentLoaded", function () {
    const chapterSteps = [
        {
            selector: ".boxes .box", // chapter box
            text: "Select a chapter to begin the lesson."
        },
        {
            selector: ".progress-done", // progress bar
            text: "Progress should display inside a Progress bar."
        },
        {
            selector: null,
            text: "You're all set!",
            isFinal: true
        }
    ];

    let currentStep = 0;

    function startChapterTutorial() {
        if (localStorage.getItem("chapterTutorialDisabled")) return;
        document.getElementById("chapterTutorialOverlay").style.display = "flex";
        showChapterStep();
    }

    function showChapterStep() {
        const step = chapterSteps[currentStep];
        const box = document.getElementById("chapterTutorialBox");
        const text = document.getElementById("chapterTutorialText");

        document.querySelectorAll(".tutorial-highlight").forEach(el => el.classList.remove("tutorial-highlight"));

        if (step.isFinal) {
            text.innerText = step.text;
            document.getElementById("chapterNextBtn").style.display = "none";
            document.getElementById("chapterPrevBtn").style.display = "none";
            document.getElementById("chapterDisableTutorialBtn").style.display = "inline-block";
            document.getElementById("chapterRemindLaterBtn").style.display = "inline-block";
        } else {
            text.innerText = step.text;
            document.getElementById("chapterNextBtn").style.display = "inline-block";
            document.getElementById("chapterPrevBtn").style.display = currentStep > 0 ? "inline-block" : "none";
            document.getElementById("chapterDisableTutorialBtn").style.display = "none";
            document.getElementById("chapterRemindLaterBtn").style.display = "none";

            const el = document.querySelector(step.selector);
            if (el) {
                el.classList.add("tutorial-highlight");
                const rect = el.getBoundingClientRect();
                box.style.top = `${rect.top + window.scrollY + rect.height + 10}px`;
                box.style.left = `${rect.left + window.scrollX}px`;
                el.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    }

    function nextChapterStep() {
        currentStep++;
        if (currentStep < chapterSteps.length) {
            showChapterStep();
        }
    }

    function prevChapterStep() {
        if (currentStep > 0) {
            currentStep--;
            showChapterStep();
        }
    }

    function endChapterTutorial() {
        document.getElementById("chapterTutorialOverlay").style.display = "none";
        document.querySelectorAll(".tutorial-highlight").forEach(el => el.classList.remove("tutorial-highlight"));
    }

    function disableChapterTutorial() {
        localStorage.setItem("chapterTutorialDisabled", "true");
        endChapterTutorial();
    }

    function remindChapterLater() {
        localStorage.removeItem("chapterTutorialDisabled");
        endChapterTutorial();
    }

    document.getElementById("chapterNextBtn").addEventListener("click", nextChapterStep);
    document.getElementById("chapterPrevBtn").addEventListener("click", prevChapterStep);
    document.getElementById("chapterDisableTutorialBtn").addEventListener("click", disableChapterTutorial);
    document.getElementById("chapterRemindLaterBtn").addEventListener("click", remindChapterLater);

    // Delay tutorial start
    setTimeout(startChapterTutorial, 2000);
});

</script> -->

          
<script>
    document.addEventListener('DOMContentLoaded', function() {
    const progressBars = document.querySelectorAll('.progress-done');

    progressBars.forEach(bar => {
        // Get the percentage from the data-done attribute
        const percentage = parseFloat(bar.getAttribute('data-done'));

        // Update the width of the progress bar
        bar.style.width = percentage + '%';

        // Ensure visibility
        bar.style.opacity = 1;
    });
});

</script>