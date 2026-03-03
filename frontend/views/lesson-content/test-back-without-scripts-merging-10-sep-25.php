<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\models\TopicIndexQuestionOptions;
use yii\helpers\Url;

$this->title = 'Test';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
// In your view file, before the <script> tag, inject the PHP variables:
$lessonId   = $lesson_id;
$studentId  = Yii::$app->session->get('current_student')['id'];
?>

<script>
  document.addEventListener('DOMContentLoaded', function() {
  var lessonId  = <?= json_encode($lessonId) ?>;
  var studentId = <?= json_encode($studentId) ?>;
  var storageKey = "lastVisitedSlide_" + lessonId + "_" + studentId;

  // **CLEAR localStorage for this quiz on load to always start fresh**
  localStorage.removeItem(storageKey);

  var savedSlide = localStorage.getItem(storageKey);
  savedSlide = (savedSlide === null) ? 0 : parseInt(savedSlide, 10);

  var totalSlides = $('.quiz_slide').length;
  if (savedSlide >= totalSlides) {
    savedSlide = 0;
  }

  $('.lesson_slides').slick({
    initialSlide : savedSlide,
    infinite     : false,
    slidesToShow : 1,
    slidesToScroll: 1
  });

  $('.lesson_slides').on('afterChange', function(event, slick, currentSlide) {
    localStorage.setItem(storageKey, currentSlide);
    $('#current-question-index').text(currentSlide + 1);
  });
});

</script>

<div class="top_bar_tuts tutorials_page test_page">
    <div class="inner">
        <div class="col1">
            <a href="<?= Url::to(['lesson-content/tutorial', 'lesson_id' => $lesson->id]) ?>" class="close_btn">
                <i class="fas fa-times"></i>
            </a>
        </div>
        <div class="col2">
            <ul class="test_navi">
                <li><?= Html::a('Hint', '#solutionModal', ['data-bs-toggle' => 'modal', 'id'=>'test1']) ?></li>
                <li>
                    <a href="<?= Yii::$app->urlManager->createUrl(['lesson-content/tutorial', 'lesson_id' => $lesson_id]) ?>" onclick="return handleTutorialClick(event);">
                        Tutorial
                    </a>
                </li>

            </ul>
        </div>
        <div class="col3">
            <!-- Display Current Question Index -->
            <div id="question-progress" class="question_progress">
                <p> Question <span id="current-question-index">1</span> of <?= $totalQuestions ?></p>
                
            </div>
        </div>
    </div>
</div>

<div class="topic_page tutorials_page test_page">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-12">

                <div id="solutionDiv" class="solution_div" style="display: none; margin-top: 20px;">
                        <h2>Answer</h2>

                    <div class="sol_text">
                        <span id="explanationText"></span>
                        <span id="solutionText"></span>
                    </div>

                    <button id="nextSlideBtn" class="theme_btn gotid_btn">Ok, Got it</button>
                </div>
                

             
                
                <?php //$form = ActiveForm::begin(['id' => 'quizForm', 'action' => ['save-quiz']]); ?>
                <?php $form = ActiveForm::begin([
                'id' => 'quizForm',
                'action' => \yii\helpers\Url::to(['lesson-content/save-quiz']),
                ]); ?>

                <input type="hidden" name="lesson_id" value="<?= Html::encode($lesson_id) ?>">
                <input type="hidden" name="test_completed" value="1">
                <input type="hidden" id="currentSlideIndex" name="current_slide_index" value="0">
                <input type="hidden" id="timeSpent" name="time_spent" value="0"> <!-- Hidden field for time spent -->
                
                <?php if (!empty($lessonContent)): ?>
                    <div class="lesson_slides">
               
                      
 
                    <?php foreach ($lessonContent as $index => $question): ?>
                    <div class="quiz_slide<?= $index === 0 ? ' active' : '' ?>" data-question-id="<?= $question->question_id ?>">
                        <div class="inner">
                        <?php $cleanTitle = trim(strip_tags(str_replace('&nbsp;', ' ', $question->title))); ?>
                        <h2 class="heading"><?= $question->title ?></h2>
                        <div class="radio_choices">

                            <?php $options = \frontend\models\TopicIndexQuestionOptions::find()
                                    ->where(['question_id' => $question->question_id])->all(); ?>

                            <!-- Hidden Inputs -->
                            <input type="hidden" class="question_id" value="<?= Html::encode($question->question_id); ?>">
                            <input type="hidden" class="answer_id" value="<?= !empty($options) ? Html::encode($options[0]->option_value) : ''; ?>">

                            <?php if (!empty($options)): foreach ($options as $option): ?>
                            <?php
                                // strip <p> and nbsp
                                $cleanOptionValue = preg_replace('/<\/?p>/', '', $option->option_value);

                                // --- IMPORTANT: neutralize any <button> coming from legacy data ---
                                // Option A) convert <button ...>...</button> into <div ...>...</div>
                                $cleanOptionValue = preg_replace('/<button\b([^>]*)>/i', '<div $1>', $cleanOptionValue);
                                $cleanOptionValue = preg_replace('/<\/button>/i', '</div>', $cleanOptionValue);

                                // (Alternative if you prefer to keep button look)
                                // $cleanOptionValue = preg_replace('/<button(?![^>]*\btype=)/i', '<button type="button"', $cleanOptionValue);
                                // $cleanOptionValue = preg_replace('/\btype=("|\')?submit\1/i', 'type="button"', $cleanOptionValue);
                            ?>
                            <label class="custom-radio">
                                <input type="radio"
                                    name="answers[<?= $question->question_id ?>]"
                                    value="<?= Html::encode($option->option_value) ?>"
                                    class="answer-option">
                                <span><?= $cleanOptionValue /* raw HTML allowed */ ?></span>
                            </label>
                            <?php endforeach; else: ?>
                            <p>No options available</p>
                            <?php endif; ?>

                        </div>
                        </div>
                    </div>
                    <?php endforeach; ?>



                    </div>
                <?php else: ?>
                    <p>No questions found for this lesson.</p>
                <?php endif; ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>



    <div class="modal fade" id="solutionModal" tabindex="-1" aria-labelledby="solutionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">

                    <div class="solution One Pehla">
                        <div class="explanations">
                             <?php if (!empty($explanations)): // Check if explanations are available ?>
                     
                                <ul>
                                    <?php foreach ($explanations as $questionId => $explanationArray): ?>
                                        <?php if ($questionId == $currentQuestionId): // Check if the current question ID matches ?>
                                            <?php foreach ($explanationArray as $explanation): ?>
                                                
                                                <li><?php echo htmlspecialchars($explanation->explanation); ?></li>

                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <p>No explanations available.</p>
                            <?php endif; ?>
                        </div>

                        <button id="modalNextSlideBtn" class="theme_btn gotid_btn">Ok, Got it</button>

                    </div>

                    
                </div>
            </div>
        </div>
    </div>

</div>


<!-- Intro.js (same as other pages) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.1.0/introjs.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.1.0/intro.min.js"></script>

<script>
(function () {
  const LESSON = <?= json_encode($lesson_id) ?>;
  const KEY    = 'wizardDismissed_test_' + LESSON;

  // Show only if not dismissed before for this lesson
  if (localStorage.getItem(KEY)) return;

  const closeBtn     = document.querySelector('.close_btn');
  const hintLink     = document.getElementById('test1');
  const tutorialLink = document.querySelector('.test_navi li:nth-child(2) a');

  // Grab the currently visible question box (works with slick)
  function currentQuestionBox() {
    return document.querySelector('.quiz_slide.slick-active .inner')
        || document.querySelector('.quiz_slide .inner')
        || document.querySelector('.lesson_slides');
  }

  const steps = [
    closeBtn && {
      element: closeBtn,
      intro: 'This will exit your test. Are you sure you want to leave?'
    },
    hintLink && {
      element: hintLink,
      intro: 'Only 3 attempts are allowed for Hints/Tutorials in a single test.'
    },
    tutorialLink && {
      element: tutorialLink,
      intro: 'Open the lesson tutorial to review concepts.'
    },
    { // question box (dynamic)
      element: currentQuestionBox(),
      intro: 'Pick one answer. Correct moves you forward automatically; wrong shows a hint.'
    },
    { intro: 'Setup Complete!', disableInteraction: true }
  ].filter(Boolean);

  const intro = introJs();
  intro.setOptions({
    steps,
    showStepNumbers: false,
    showBullets: false,
    overlayOpacity: 0.6,
    exitOnOverlayClick: true,
    showButtons: true,
    nextLabel: 'Next',
    prevLabel: 'Back'
  });

  // When coming to question step, re-point element to the active slide
  intro.onbeforechange(function () {
    // question step index = last step - 2 (because final step has no element)
    const questionStepIndex = intro._introItems.length - 2;
    if (intro._currentStep === questionStepIndex) {
      intro._introItems[questionStepIndex].element = currentQuestionBox();
    }
  });

  // Last step → replace footer with our two buttons
  function patchFooterIfLast() {
    const isLast = intro._currentStep === intro._introItems.length - 1;
    const footer = document.querySelector('.introjs-tooltipbuttons');
    if (!footer) return;

    if (isLast) {
      footer.innerHTML = '';

      const dont = document.createElement('button');
      dont.className = 'introjs-button custom-gray';
      dont.textContent = "Don't show again";
      dont.onclick = function () {
        localStorage.setItem(KEY, '1');
        intro.exit();
      };

      const ok = document.createElement('button');
      ok.className = 'introjs-button';
      ok.textContent = 'OK, got it';
      ok.onclick = function () { intro.exit(); };

      footer.appendChild(dont);
      footer.appendChild(ok);
    }
  }
  intro.onafterchange(patchFooterIfLast);
  intro.onchange(patchFooterIfLast);

  // Start after DOM/slick is ready
  setTimeout(() => intro.start(), 500);
})();
</script>

<style>
  .introjs-helperLayer { box-shadow: 0 0 0 3px yellow !important; border-radius: 5px; }
  .introjs-button { background:#1EB2A6 !important; color:#fff !important; border:none !important; padding:6px 12px !important; }
  .introjs-button:hover { background:#17a093 !important; }
  .custom-gray { background:#ddd !important; color:#000 !important; margin-right:10px !important; }
  .custom-gray:hover { color:#fff !important; }
</style>




<?php
$this->registerJs('
$(document).ready(function () {
    var totalSlides = $(".quiz_slide").length;
    var formSubmitting = false;
    var startTime = new Date().getTime();
    var lessonId = ' . json_encode($lesson_id) . ';
    var studentId = ' . json_encode(Yii::$app->session->get("current_student")["id"]) . ';
    var storageKey = "lastVisitedSlide_" + lessonId + "_" + studentId;
    var hintKey = "solutionModalCount_" + lessonId;

    // Reset hint count on test start (fresh attempt)
    localStorage.removeItem(hintKey);

    // Reset hint count if student clicks close icon (back to tutorial)
    $(".close_btn").on("click", function () {
        localStorage.removeItem(hintKey);
    });

    function submitQuizIfLastSlide() {
        var currentSlideIndex = $(".lesson_slides").slick("slickCurrentSlide");
        if (currentSlideIndex === totalSlides - 1 && !formSubmitting) {
            formSubmitting = true;

            // Reset hint count on test completion
            localStorage.removeItem(hintKey);

            var endTime = new Date().getTime();
            var timeSpent = Math.round((endTime - startTime) / 1000);
            $("#timeSpent").val(timeSpent);

            var formData = $("#quizForm").serialize();
             
            $.ajax({
                url: $("#quizForm").attr("action"),
                type: "post",
                data: formData,
                dataType: "json",              // server se JSON aa raha
                success: function (res, status, xhr) {
                    console.log("save-quiz response:", res);

                    if (res && res.ok === true && res.redirectUrl) {
                        window.location.href = res.redirectUrl;   // server se sahi URL
                        return;
                    }

                    console.error("save-quiz NOT OK:", res);
                    alert((res && (res.dbMessage || res.msg)) ? (res.dbMessage || res.msg) : "Could not save attempt. See console.");
                    formSubmitting = false;
                },
                error: function (xhr, status, error) {
                    console.error("AJAX 500:", xhr.status, error);
                    console.error("Response:", xhr.responseText);
                    alert("Server error while saving quiz. See console.");
                    formSubmitting = false;
                }
            });


        }
    }

  $(".answer-option").change(function () {
    var currentSlide = $(this).closest(".quiz_slide");
    var questionId = currentSlide.data("question-id");
    var selectedAnswer = $(this).val();
    if (!selectedAnswer) return;

    $.ajax({
        url: "' . Yii::$app->urlManager->createUrl(["lesson-content/check-answer"]) . '",
        type: "post",
        data: { question_id: questionId, selectedAnswer: selectedAnswer },
        success: function (response) {
            currentSlide.find(".custom-radio i").remove();

            if (response.is_correct) {
                // ✅ Correct Answer
               var checkMark = "<i class=\\"fas fa-check correct\\"></i>";
                    currentSlide.find("input[name=\\"answers[" + questionId + "]\\"]:checked").closest(".custom-radio").append(checkMark);

                // ❌ Make sure hint box is hidden
                $("#solutionDiv").hide();

                var currentIndex = $(".lesson_slides").slick("slickCurrentSlide");
                if (currentIndex === totalSlides - 1) {
                    submitQuizIfLastSlide();
                } else {
                    setTimeout(function () {
                        $(".lesson_slides").slick("slickNext");
                        updateSlideIndex();
                    }, 500);
                }

            } else {
                // ❌ Wrong Answer — show Hint
                var crossMark = "<i class=\\"fas fa-times incorrect\\"></i>";
                    currentSlide.find("input[name=\\"answers[" + questionId + "]\\"]:checked").closest(".custom-radio").append(crossMark);
                currentSlide.find(".answer-option").prop("disabled", true);

                var solution = $("<div>").html(response.solution); // HTML to DOM
                var explanation = response.explanation || "No explanation available";

                $("#explanationText").html(explanation);

                if (solution.find("img").length > 0) {
                    $("#solutionText").html(solution.find("img"));
                } else {
                    $("#solutionText").text(solution.text());
                }

                // ✅ Only show on wrong
                setTimeout(function () {
                    $("#solutionDiv").fadeIn();
                }, 50);
            }
        }
    });
});






    $("#modalNextSlideBtn, #nextSlideBtn").click(function () {
        var currentIndex = $(".lesson_slides").slick("slickCurrentSlide");
        $("#solutionDiv").hide();
        $("#solutionModal").modal("hide");
        $(".quiz_slide").each(function () {
            $(this).find(".answer-option").prop("disabled", false);
        });

        if (currentIndex === totalSlides - 1) {
            updateSlideIndex();
            submitQuizIfLastSlide();
        } else {
            $(".lesson_slides").slick("slickNext");
            updateSlideIndex();
        }
    });

       $("#test1").on("click", function (e) {
            e.preventDefault();

            var modalOpenCount = parseInt(localStorage.getItem(hintKey) || "0", 10);
            var modalOpenLimit = 3;

            if (modalOpenCount >= modalOpenLimit) {
                $("#solutionModal .modal-body").html(`
                    <p>You have reached the limit of 3 Hint views for this lesson.</p>
                    <button type="button" class="theme_btn gotid_btn" id="closeModalBtn">Close</button>
                `);
                $("#solutionModal").modal("show");
                $("#closeModalBtn").off("click").on("click", function () {
                    $("#solutionModal").modal("hide");
                });
                return;
            }

            var activeSlide = $(".quiz_slide.slick-active");
            var questionId = activeSlide.data("question-id");

            // Always get correct answer from hidden input (not selected one)
            var selectedAnswer = activeSlide.find(".answer-option:checked").val();
            if (!selectedAnswer || selectedAnswer.trim() === "") {
                selectedAnswer = activeSlide.find(".answer_id").val(); // ✅ this will be correct answer
            }

            if (!questionId || !selectedAnswer) return;

            $("#solutionModal .modal-body").html("<p>Loading...</p>");
            $("#solutionModal").modal("show");

            $.ajax({
                url: "' . Yii::$app->urlManager->createUrl(["lesson-content/check-answer"]) . '",
                type: "post",
                data: {
                    question_id: questionId,
                    selectedAnswer: selectedAnswer,
                    showExplanationAnyway: true // optional if backend expects flag
                },
                success: function (response) {
                    modalOpenCount++;
                    localStorage.setItem(hintKey, modalOpenCount);

                    var explanationHtml = response.explanation || "No explanation available";
                    var solutionHtml = "";

                    if ($(response.solution).find("img").length > 0) {
                        solutionHtml = $("<div>").append($(response.solution).find("img")).html();
                    } else {
                        solutionHtml = $("<div>").append(response.solution).text();
                    }

                    var modalBody = `
                        <div class="solution one1">
                            <div class="explanations">${explanationHtml}</div>
                            <div id="modalSolutionText">${solutionHtml}</div>
                            <button type="button" class="theme_btn gotid_btn" id="modalNextSlideBtn">OK, Got it</button>
                        </div>`;

                    $("#solutionModal .modal-body").html(modalBody);

                    $("#modalNextSlideBtn").off("click").on("click", function () {
                        $("#solutionModal").modal("hide");
                        $(".lesson_slides").slick("slickNext");
                        updateSlideIndex();
                        submitQuizIfLastSlide();
                    });
                }
            });
        });



    function updateSlideIndex() {
        var currentSlideIndex = $(".lesson_slides").slick("slickCurrentSlide");
        $("#currentSlideIndex").val(currentSlideIndex);
        $("#current-question-index").text(currentSlideIndex + 1);
        localStorage.setItem(storageKey, currentSlideIndex);
    }

    $(".lesson_slides").on("afterChange", function () {
        updateSlideIndex();
    });
});
');
?>






<script>
function goBack() {
    var originalReferrerUrl = <?= json_encode(Yii::$app->session->get('originalReferrerUrl')) ?>;
    if (originalReferrerUrl) {
        window.location.href = originalReferrerUrl;
    } else {
        window.history.back();
    }
}


</script>


<script>
    $(document).ready(function() {
    
    var solution_count = 0;
    var totalSlides = $('.quiz_slide').length;
    
    // Initialize the current question index
    updateSlideIndex(0); 
    
            $('.lesson_slides').on('afterChange', function(event, slick, currentSlide) {
                updateSlideIndex(currentSlide + 1);

                var questionId = $('.slick-active .question_id').val();
                var selectedAnswer = $('.slick-active .answer_id').val();

                $.ajax({
                    url: "<?php echo Yii::$app->urlManager->createUrl(['lesson-content/check-answer']) ?>",
                    type: 'post',
                    data: { question_id: questionId, selectedAnswer: selectedAnswer },
                    success: function(response) {
                        // Hide solutionDiv by default (important!)
                        $("#solutionDiv").hide();

                        // Only show if answer is wrong
                        if (response.is_correct === false || response.correct === false) {
                            $('#explanationText').html(response.explanation);

                            var solution = response.solution;
                            var solutionText = $('#solutionText');

                            if ($(solution).find('img').length > 0) {
                                var imgTag = $(solution).find('img');
                                solutionText.html(imgTag);
                            } else {
                                var textContent = $(solution).text();
                                solutionText.text(textContent);
                            }

                            setTimeout(function() {
                                $("#solutionDiv").fadeIn();
                            }, 50);
                        } else {
                            $("#solutionDiv").hide(); 
                        }
                    }
                });
            });



    });

    
    function updateSlideIndex(currentIndex) {
        $('#current-question-index').text(currentIndex); 
    }   


      

        var lessonId = <?= $lesson_id ?>;
        var modalOpenCountKey = 'solutionModalCount_' + lessonId;
        var modalOpenCount = localStorage.getItem(modalOpenCountKey) ? parseInt(localStorage.getItem(modalOpenCountKey)) : 0;
        var modalOpenLimit = 3; 

         
        // Handle 'Ok, Got it' button click in the modal
        $('#modalNextSlideBtn').click(function() {
            $('.lesson_slides').slick('slickNext');
            $('#solutionModal').modal('hide');
            updateSlideIndex();
        });



     



});


</script>



<script>
  // Function to save the current slide index using a unique key per quiz
  function saveCurrentSlideIndex() {
    var storageKey = "lastVisitedSlide_<?= $lesson_id ?>";
    if ($('.lesson_slides').hasClass('slick-initialized')) {
      var currentSlideIndex = $('.lesson_slides').slick('slickCurrentSlide');
      console.log("Saving slide index:", currentSlideIndex);
      localStorage.setItem(storageKey, currentSlideIndex);
    } else {
      console.log("Slider not initialized yet.");
    }
  }

   
  $(document).ready(function() {
    var storageKey = "lastVisitedSlide_<?= $lesson_id ?>";
    var quizCompletedKey = "quizCompleted_<?= $lesson_id ?>";
    
     
    if (localStorage.getItem(quizCompletedKey) === "true") {
      console.log("Quiz is completed. Starting from slide 0.");
      localStorage.removeItem(storageKey);  
      localStorage.removeItem(quizCompletedKey); 
    }
    
    var savedSlide = localStorage.getItem(storageKey);
    if (savedSlide === null) {
      savedSlide = 0;
    } else {
      savedSlide = parseInt(savedSlide, 10);
    }
    
    
    var totalSlides = $('.quiz_slide').length;
    if (savedSlide >= totalSlides) {
      savedSlide = 0;
    }
    
    console.log("Starting Slide Index:", savedSlide);
    
     
    $('.lesson_slides').slick({
      initialSlide: savedSlide,
      infinite: false,
      slidesToShow: 1,
      slidesToScroll: 1
    });
    
     
    $('.lesson_slides').slick('slickGoTo', savedSlide);
    
    // Update and save the slide index on every slide change
    $('.lesson_slides').on('afterChange', function(event, slick, currentSlide) {
      console.log("After change, current slide:", currentSlide);
      $('#current-question-index').text(currentSlide + 1);
      localStorage.setItem(storageKey, currentSlide);
    });
  });
 


</script>

<script>
   
  function saveCurrentQuestionId() {
    var storageKey = "lastVisitedQuestion_<?= $lesson_id ?>";
    if ($('.lesson_slides').hasClass('slick-initialized')) {
      var currentQuestionId = $('.quiz_slide.slick-active').data('question-id');
      console.log("Saving current question id:", currentQuestionId);
      localStorage.setItem(storageKey, currentQuestionId);
    } else {
      console.log("Slider not initialized yet.");
    }
  }

   
  function handleTutorialClick(event) {
    var tutorialKey = "tutorialPressCount_<?= $lesson_id ?>";
    var pressCount = parseInt(localStorage.getItem(tutorialKey) || "0", 10);
    console.log("Tutorial button pressed count:", pressCount);
    
    if (pressCount >= 3) {
      alert("You have exceeded the tutorial limit (3 times). You cannot access the tutorial again. Finish the test to reset the tutorial limit.");
      event.preventDefault();
      return false;
    } else {
      pressCount++;
      localStorage.setItem(tutorialKey, pressCount);
      // Call your existing function to save the current question/slide
      saveCurrentQuestionId();
      return true;
    }
  }
</script>