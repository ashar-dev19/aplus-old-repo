<?php
   use yii\helpers\Html;
   use yii\widgets\ActiveForm;
   use frontend\models\TopicIndexQuestionOptions;
   use yii\helpers\Url;
   
   $this->title = 'Test';
   $this->params['breadcrumbs'][] = $this->title;
   ?>
   
<?php
   function sanitizeWordHtml(string $html, bool $preserveYellow = false): string
   {
       $h = (string)$html;
   
        
       if ($preserveYellow) {
           $h = preg_replace(
               '/<span[^>]*?(background\s*:\s*yellow|mso-highlight\s*:\s*yellow)[^>]*>(.*?)<\/span>/is',
               '[[HL]]$2[[/HL]]',
               $h
           );
       }
   
       // general clean
       $h = str_replace('&nbsp;', ' ', $h);
       $h = preg_replace('/<\/?(p|span|font)[^>]*>/i', '', $h);
       $h = preg_replace('/<button\b([^>]*)>/i', '<div $1>', $h);
       $h = preg_replace('/<\/button>/i', '</div>', $h);
       $h = preg_replace('/\s+/', ' ', $h);
       $h = trim($h);
   
       // yellow tokens wapas span me
       if ($preserveYellow) {
           $h = str_replace(['[[HL]]','[[/HL]]'], ['<span class="ms-hl">','</span>'], $h);
       }
       return $h;
   }
   ?>
<?php
   $lessonId   = $lesson_id;
   $studentId  = Yii::$app->session->get('current_student')['id'];
   
   // sirf is lesson ke liye shuffle on
   // $ONLY_RANDOM_LESSON = 1306;
   // $isRandomLesson = ((int)$lesson_id === $ONLY_RANDOM_LESSON);
   
   // All lessons: always shuffle options
   $shuffleThisLesson = true;
   // $DONT_SHUFFLE = [9999, 8888];
   // $shuffleThisLesson = !in_array((int)$lesson_id, $DONT_SHUFFLE, true);
   
    
   $AFFECTED   = [701, 1311, 1313, 3662, 3663, 1801, 1447, 26, 1581];
   $isAffected = in_array((int)$lesson_id, $AFFECTED, true);
   
   
   // jin lessons me yellow highlight UI me dikhani hai
   $PRESERVE_YELLOW = [1311,1313];
   $keepYellow = in_array((int)$lesson_id, $PRESERVE_YELLOW, true);
   
   ?>
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
<div class="topic_page tutorials_page test_page lesson-<?= (int)$lesson_id ?>">
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

                            <?php
                            // Options: always shuffled (SQL RAND ok for small sets)
                            if ($shuffleThisLesson) {
                                $options = \frontend\models\TopicIndexQuestionOptions::find()
                                    ->where(['question_id' => $question->question_id])
                                    ->orderBy(new \yii\db\Expression('RAND()'))
                                    ->all();

                                // // PHP variant (DB RAND avoid karna ho to):
                                // $options = \frontend\models\TopicIndexQuestionOptions::find()
                                //     ->where(['question_id' => $question->question_id])
                                //     ->all();
                                // shuffle($options);

                            } else {
                                $options = \frontend\models\TopicIndexQuestionOptions::find()
                                    ->where(['question_id' => $question->question_id])
                                    ->all();
                            }
                            ?>

                            <!-- Hidden Inputs -->
                            <input type="hidden" class="question_id" value="<?= Html::encode($question->question_id); ?>">

                            <?php 
                            // $correctAnsRaw = \frontend\models\TestQuestionAnswer::find()
                            //     ->select('answer')
                            //     ->where(['question_id' => $question->question_id])
                            //     ->scalar();

                            // $correctValueAttr = $isAffected
                            //     ? Html::encode(sanitizeWordHtml((string)$correctAnsRaw, false))
                            //     : (string)$correctAnsRaw;
                            ?>
                            <?php
                              // Always fetch correct answer from DB (independent of option order)
                              // $correctAnsRaw = \frontend\models\TestQuestionAnswer::find()
                              //     ->select('answer')
                              //     ->where(['question_id' => $question->question_id])
                              //     ->scalar();

                              // // IMPORTANT: attribute ke liye ALWAYS escape (quotes bhi)
                              // $correctValueAttr = Html::encode(
                              //     sanitizeWordHtml((string)$correctAnsRaw, false)
                              // );
                              ?>

                              <?php 
                             // Always fetch correct answer from DB (independent of option order)
                                $correctAnsRaw = \frontend\models\TestQuestionAnswer::find()
                                    ->select('answer')
                                    ->where(['question_id' => $question->question_id])
                                    ->scalar();

                                // Sirf PRESERVE_YELLOW lessons (1311, 1313) ke liye
                                // exact RAW HTML use karo, taake DB answer se 1:1 match ho
                                if ($keepYellow) {
                                    $correctValueAttr = Html::encode((string)$correctAnsRaw);
                                } else {
                                    // baaki sab ke liye tumhara purana behaviour
                                    $correctValueAttr = Html::encode(
                                        sanitizeWordHtml((string)$correctAnsRaw, false)
                                    );
                                }


                                ?>

                            <input type="hidden" class="answer_id" value="<?= $correctValueAttr ?>">

                            <?php if (!empty($options)): foreach ($options as $option): ?>
                                <?php
                                // if ($isAffected) {
                                //     // VALUE (submit hone wali) – span hatao
                                //     $valueAttr        = Html::encode(sanitizeWordHtml($option->option_value, false));
                                //     // UI (student ko dikhana) – sirf yellow highlight allow
                                //     $cleanOptionValue = sanitizeWordHtml($option->option_value, $keepYellow);
                                // } else {
                                //     // aapka purana light cleanup
                                //     $cleanOptionValue = preg_replace('/<\/?p>/', '', $option->option_value);
                                //     $cleanOptionValue = preg_replace('/<button\b([^>]*)>/i', '<div $1>', $cleanOptionValue);
                                //     $cleanOptionValue = preg_replace('/<\/button>/i', '</div>', $cleanOptionValue);
                                //     $valueAttr        = Html::encode($option->option_value);
                                // }

                                 if ($isAffected) {

                                    if ($keepYellow) {
                                        //SIRF lessons 1311 / 1313:

                                        // 1) VALUE = original HTML (as-is) taake controller ko
                                        //    wohi string mile jo TestQuestionAnswer.answer me hai.
                                        $valueAttr = Html::encode($option->option_value);

                                        // 2) UI = cleaned + yellow highlight spans
                                        $cleanOptionValue = sanitizeWordHtml($option->option_value, true);

                                    } else {
                                        // BAAKI AFFECTED LESSONS – tumhara purana behaviour
                                        $valueAttr        = Html::encode(sanitizeWordHtml($option->option_value, false));
                                        $cleanOptionValue = sanitizeWordHtml($option->option_value, $keepYellow);
                                    }

                                } else {
                                    // NON-AFFECTED lessons – purani simple cleaning
                                    $cleanOptionValue = preg_replace('/<\/?p>/', '', $option->option_value);
                                    $cleanOptionValue = preg_replace('/<button\b([^>]*)>/i', '<div $1>', $cleanOptionValue);
                                    $cleanOptionValue = preg_replace('/<\/button>/i', '</div>', $cleanOptionValue);
                                    $valueAttr        = Html::encode($option->option_value);
                                }


                                


                                ?>

                                <label class="custom-radio">
                                    <input type="radio"
                                        name="answers[<?= $question->question_id ?>]"
                                        value="<?= $valueAttr ?>"
                                        class="answer-option">
                                    <span class="option-html"><?= $cleanOptionValue  ?></span>
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
   
       // NEW:
   var HINT_LIMIT = 4;   
        
             // ----- HINT bubble UI next to the #test1 link  
             var $hintLink = $("#test1");
             if ($hintLink.length && !$hintLink.next("#hintCountBadge").length) {
               $hintLink.after(
                 ` <span id="hintCountBadge" class="hint-count-badge"
                     style="display:none;margin-left:8px;padding:2px 8px;border:1px solid rgba(0,0,0,.2);
                             border-radius:999px;font-weight:600;font-size:12px;line-height:1.6;background:#f5f5f5;">
                     <span id="hintCountUsed">0</span> of ${HINT_LIMIT}
                   </span>`
               );
             }
   
   
         // helpers to show/hide/update the bubble
         function refreshHintBubble() {
           var used = parseInt(localStorage.getItem(hintKey) || "0", 10);
           if (isNaN(used) || used <= 0) {
             $("#hintCountBadge").hide();
           } else {
             $("#hintCountUsed").text(Math.min(used, HINT_LIMIT));
             $("#hintCountBadge").show();
           }
         }
   
         function previewHintBubble(nextUsed) {
           $("#hintCountUsed").text(Math.min(nextUsed, HINT_LIMIT)); 
           $("#hintCountBadge").show();
         }
   
         // close icon par reset + bubble hide (aapke reset ke sath)
         $(".close_btn").on("click", function () {
             $("#hintCountBadge").hide();
         });
    
       
   
   
   
       // Reset hint count on test start (fresh attempt)
       localStorage.removeItem(hintKey);
   
         refreshHintBubble();
   
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
                   // Correct Answer
                  var checkMark = "<i class=\\"fas fa-check correct\\"></i>";
                       currentSlide.find("input[name=\\"answers[" + questionId + "]\\"]:checked").closest(".custom-radio").append(checkMark);
   
                   // Make sure hint box is hidden
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
                   // Wrong Answer — show Hint
                   var crossMark = "<i class=\\"fas fa-times incorrect\\"></i>";
                       currentSlide.find("input[name=\\"answers[" + questionId + "]\\"]:checked").closest(".custom-radio").append(crossMark);
                   currentSlide.find(".answer-option").prop("disabled", true);
   
                   var solution = $("<div>").html(response.solution); // HTML to DOM
                   var explanation = response.explanation || "";
   
                   $("#explanationText").html(explanation);
   
                   if (solution.find("img").length > 0) {
                       $("#solutionText").html(solution.find("img"));
                   } else {
                       $("#solutionText").text(solution.text());
                   }
   
                   // Only show on wrong
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
               var modalOpenLimit = HINT_LIMIT;
   
                 // Show upcoming count in the bubble (1/3, 2/3, 3/3)
                 if (modalOpenCount < modalOpenLimit) {
                     previewHintBubble(modalOpenCount + 1);
                 } else {
                     previewHintBubble(modalOpenLimit);
                 }
   
               if (modalOpenCount >= modalOpenLimit) {
                 previewHintBubble(modalOpenLimit);
   
                   $("#solutionModal .modal-body").html(`
                       <p>You have reached the limit of ${HINT_LIMIT} Hint views for this lesson.</p>
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
                   selectedAnswer = activeSlide.find(".answer_id").val(); //  this will be correct answer
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
   
                       refreshHintBubble();   // <— bubble ko final value (1,2,3) par set karo
   
                       var explanationHtml = response.explanation || "";
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
<!-- ONE-BLOCK REPLACEMENT: put near the end of the file -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.1.0/introjs.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.1.0/intro.min.js"></script>
<style>
   .introjs-helperLayer{box-shadow:0 0 0 3px yellow!important;border-radius:6px}
   .introjs-button{background:#1EB2A6!important;color:#fff!important;border:none!important;padding:6px 12px!important}
   .introjs-button:hover{background:#179f92!important}
   .custom-gray{background:#ddd!important;color:#000!important;margin-right:10px!important}
   .custom-gray:hover{color:#fff!important}
   /* small perf/UX touches */
   .slick-track,.slick-slide{will-change:transform}
   .radio_choices img{max-width:100%;height:auto}
</style>
<script>
   (function($){
     // -------------------- BASIC SETUP (IDs & KEYS) --------------------
     const LESSON_ID  = <?= json_encode($lesson_id) ?>;
     const STUDENT_ID = <?= json_encode(Yii::$app->session->get('current_student')['id']) ?>;
     const CHECK_URL  = '<?= Yii::$app->urlManager->createUrl(['lesson-content/check-answer']) ?>';
     const SAVE_URL   = '<?= \yii\helpers\Url::to(['lesson-content/save-quiz']) ?>';
   
     const SLIDE_KEY  = "lastVisitedSlide_" + LESSON_ID + "_" + STUDENT_ID;
     const HINT_KEY   = "solutionModalCount_" + LESSON_ID;
     
     const INTRO_KEY  = "wizardDismissed_test_v2_" + LESSON_ID; 
   
   
     // -------------------- SLICK: INIT (fast) --------------------
     (function initSlick(){
       // Always start fresh for tests (as your current behavior)
       localStorage.removeItem(SLIDE_KEY);
   
       var saved = parseInt(localStorage.getItem(SLIDE_KEY)||"0",10);
       var total = $('.quiz_slide').length;
       if (isNaN(saved) || saved>=total) saved = 0;
   
       $('.lesson_slides').slick({
         initialSlide  : saved,
         infinite      : false,
         slidesToShow  : 1,
         slidesToScroll: 1,
         speed         : 250,
         cssEase       : 'ease-out'
       });
     })();
   
     // -------------------- STATE / CACHES --------------------
     var totalSlides   = $(".quiz_slide").length;
     var formSubmitting= false;
     var startTime     = Date.now();
     var hintCache     = Object.create(null); // { qid: {explanation, solution} }
     var inFlight      = Object.create(null); // { qid: true }
   
     // reset hint limit on fresh test + when closing
     localStorage.removeItem(HINT_KEY);
     $(".close_btn").on("click", function(){ localStorage.removeItem(HINT_KEY); });
   
     // -------------------- HELPERS --------------------
     function updateSlideIndex(){
       var idx = $(".lesson_slides").slick("slickCurrentSlide") || 0;
       $("#currentSlideIndex").val(idx);
       $("#current-question-index").text(idx + 1);
       localStorage.setItem(SLIDE_KEY, idx);
     }
   
     function renderHint(data){
       $("#explanationText").html(data?.explanation || "");
   
       const $holder = $("<div>").html(data?.solution || "");
       const $img = $holder.find("img").first();
   
       if ($img.length){
         // preload to avoid jank
         const img = new Image();
         img.onload  = () => $("#solutionText").html($img);
         img.onerror = () => $("#solutionText").text($holder.text());
         img.src = $img.attr("src");
       } else {
         $("#solutionText").text($holder.text());
       }
       $("#solutionDiv").stop(true,true).fadeIn(120);
     }
   
     function prefetchHintFor(idx){
       var $slide = $(".quiz_slide").eq(idx);
       if (!$slide.length) return;
       var qid = String($slide.data("question-id"));
       if (hintCache[qid] || inFlight[qid]) return;
   
       var correct = $slide.find(".answer_id").val();
       if (!correct) return;
   
       inFlight[qid] = true;
       $.post(CHECK_URL, {question_id: qid, selectedAnswer: correct, showExplanationAnyway: true})
        .done(function(res){
           hintCache[qid] = { explanation: res.explanation, solution: res.solution };
           var src = $("<div>").html(res.solution||"").find("img").attr("src");
           if (src) (new Image()).src = src; // warm browser cache
        })
        .always(function(){ delete inFlight[qid]; });
     }
   
     function submitQuizIfLastSlide(){
       var current = $(".lesson_slides").slick("slickCurrentSlide");
       if (current !== totalSlides-1 || formSubmitting) return;
   
       formSubmitting = true;
       localStorage.removeItem(HINT_KEY);
   
       $("#timeSpent").val( Math.round((Date.now() - startTime)/1000) );
   
       $.ajax({
         url : SAVE_URL,
         type: "post",
         data: $("#quizForm").serialize(),
         dataType: "json"
       }).done(function(res){
         if (res && res.ok === true && res.redirectUrl){
           window.location.href = res.redirectUrl;
         } else {
           console.error("save-quiz NOT OK:", res);
           alert(res?.dbMessage || res?.msg || "Could not save attempt.");
           formSubmitting = false;
         }
       }).fail(function(xhr){
         console.error("AJAX 500:", xhr.status, xhr.responseText);
         alert("Server error while saving quiz.");
         formSubmitting = false;
       });
     }
   
     // -------------------- ANSWER CLICK (fast, single call) --------------------
     $(".lesson_slides").on("change", ".answer-option", function(){
       var $slide   = $(this).closest(".quiz_slide");
       var qid      = String($slide.data("question-id"));
       var selected = this.value;
       if (!selected) return;
       if (inFlight[qid]) return;
   
       inFlight[qid] = true;
   
       $.ajax({
         url: CHECK_URL,
         type:"post",
         dataType:"json",
         timeout:8000,
         data:{ question_id: qid, selectedAnswer: selected }
       }).done(function(res){
         $slide.find(".custom-radio i").remove();
   
         if (res && res.is_correct){
           var check = '<i class="fas fa-check correct"></i>';
           $slide.find('.answer-option:checked').closest(".custom-radio").append(check);
           $("#solutionDiv").hide();
   
           var current = $(".lesson_slides").slick("slickCurrentSlide");
           setTimeout(function(){
             if (current === totalSlides-1){
               submitQuizIfLastSlide();
             } else {
               $(".lesson_slides").slick("slickNext");
               updateSlideIndex();
             }
           }, 220);
         } else {
           // cache and render hint
           if (res) hintCache[qid] = { explanation: res.explanation, solution: res.solution };
           renderHint(hintCache[qid] || {});
           $slide.find(".answer-option").prop("disabled", true);
         }
       }).fail(function(){
         $("#explanationText").text("Could not load hint. Please try again.");
         $("#solutionText").empty();
         $("#solutionDiv").fadeIn(120);
       }).always(function(){
         delete inFlight[qid];
       });
     });
   
     // -------------------- HINT BUTTON (#test1) LIMIT 4 --------------------
     $("#test1").on("click", function(e){
       e.preventDefault();
   
       var count = parseInt(localStorage.getItem(HINT_KEY) || "0", 10);
       if (count >= HINT_LIMIT){
         $("#solutionModal .modal-body").html(
           '<p>You have reached the limit of 4 Hint views for this lesson.</p>' +
           '<button type="button" class="theme_btn gotid_btn" id="closeModalBtn">Close</button>'
         );
         $("#solutionModal").modal("show");
         $("#closeModalBtn").off("click").on("click", function(){ $("#solutionModal").modal("hide"); });
         return;
       }
   
       var $active    = $(".quiz_slide.slick-active");
       var qid        = String($active.data("question-id"));
       var correctAns = $active.find(".answer_id").val();
       if (!qid || !correctAns) return;
   
       $("#solutionModal .modal-body").html("<p>Loading...</p>");
       $("#solutionModal").modal("show");
   
       $.post(CHECK_URL, {question_id: qid, selectedAnswer: correctAns, showExplanationAnyway: true})
        .done(function(res){
           localStorage.setItem(HINT_KEY, count+1);
   
           var explanationHtml = res.explanation || "No explanation available";
           var solHtml;
           if ($("<div>").html(res.solution||"").find("img").length){
             solHtml = $("<div>").append($(res.solution).find("img")).html();
           } else {
             solHtml = $("<div>").append(res.solution||"").text();
           }
   
           $("#solutionModal .modal-body").html(
             '<div class="solution one1">'+
               '<div class="explanations">'+explanationHtml+'</div>'+
               '<div id="modalSolutionText">'+solHtml+'</div>'+
               '<button type="button" class="theme_btn gotid_btn" id="modalNextSlideBtn">OK, Got it</button>'+
             '</div>'
           );
   
           $("#modalNextSlideBtn").off("click").on("click", function(){
             $("#solutionModal").modal("hide");
             $(".lesson_slides").slick("slickNext");
             updateSlideIndex();
             submitQuizIfLastSlide();
           });
        });
     });
   
     // -------------------- OK, GOT IT (inline hint) --------------------
     $("#modalNextSlideBtn, #nextSlideBtn").on("click", function(){
       $("#solutionDiv").hide();
       $("#solutionModal").modal("hide");
       $(".quiz_slide .answer-option").prop("disabled", false);
   
       var current = $(".lesson_slides").slick("slickCurrentSlide");
       if (current === totalSlides-1){
         updateSlideIndex();
         submitQuizIfLastSlide();
       } else {
         $(".lesson_slides").slick("slickNext");
         updateSlideIndex();
       }
     });
   
     // -------------------- AFTER-CHANGE: lightweight + prefetch --------------------
     $(".lesson_slides").on("afterChange", function(e, slick, curr){
       updateSlideIndex();
       prefetchHintFor(curr);
       prefetchHintFor(curr+1);
     });
   
     // First paint
     setTimeout(function(){
       updateSlideIndex();
       var curr = $(".lesson_slides").slick("slickCurrentSlide") || 0;
       prefetchHintFor(curr);
       prefetchHintFor(curr+1);
     },0);
   
       
   
   
   
   })(jQuery);
   
   
   
   
   
</script>
<!-- tool tip script -->
<!-- <script>
   (function () {
     const LESSON = <?php //json_encode($lesson_id) ?>;
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
   </script> -->