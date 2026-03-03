<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\models\TopicIndexQuestionOptions;
use yii\helpers\Url;

$this->title = 'Test';
$this->params['breadcrumbs'][] = $this->title;
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

<div class="topic_page tutorials_page test_page">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-12">

                <div id="solutionDiv" class="solution_div" style="display: none; margin-top: 20px;">
                        <h2>Hint</h2>

                    <div class="sol_text">
                        <span id="explanationText"></span>
                        <span id="solutionText"></span>
                    </div>

                    <button id="nextSlideBtn" class="theme_btn gotid_btn">Ok, Got it</button>
                </div>
                

             
                
                <?php $form = ActiveForm::begin(['id' => 'quizForm', 'action' => ['save-quiz']]); ?>
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
                                    
                                    <?php $options = TopicIndexQuestionOptions::find()->where(['question_id' => $question->question_id])->all(); ?>
                                        
                                    
                                     <!-- Hidden Inputs -->
                <input type="hidden" class="question_id" value="<?= Html::encode($question->question_id); ?>">
                <input type="hidden" class="answer_id" value="<?= !empty($options) ? Html::encode($options[0]->option_value) : ''; ?>">

                                        <?php
                                            if (!empty($options)) {
                                              foreach ($options as $option):
                                                $cleanOptionValue = preg_replace('/<\/?p>/', '', $option->option_value);
                                        ?>
                                                <label class="custom-radio">
                                                  
                                                    <input type="radio" name="answers[<?= $question->question_id ?>]" value="<?= Html::encode($option->option_value) ?>" class="answer-option">
                                                    <span><?= $cleanOptionValue ?></span>
                                                </label>
                                        <?php
                                            endforeach;
                                        } else {
                                            echo "<p>No options available</p>";
                                        }
                                        ?>
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

                    <div class="solution">
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
$this->registerJs("
    $(document).ready(function() {
        var totalSlides = $('.quiz_slide').length;
        var formSubmitting = false; 
        var startTime = new Date().getTime(); // Track start time
       $('.slick-active .answer-option:first') .trigger('change');
        // Listen to changes on answer options
        $('.answer-option').change(function() {
            var currentSlide = $(this).closest('.quiz_slide');
            var questionId = currentSlide.data('question-id');
            var selectedAnswer = $(this).val();
            var solutionDiv = $('#solutionDiv');
            var divNextSlideBtn = $('#nextSlideBtn');

            if (selectedAnswer === undefined) return;
           

            $.ajax({
                url: '" . Yii::$app->urlManager->createUrl(['lesson-content/check-answer']) . "',
                type: 'post',
                data: { question_id: questionId, selectedAnswer: selectedAnswer },
                success: function(response) {
                     console.log(response); // Add this line to check the response
                    currentSlide.find('.custom-radio i').remove();
                   
                   

                    if (response.status === 'success') {
                         console.log('Solution:', response.solution); 
                              
                             $('#explanationText').html(response.explanation);
                              
                                // Check if the solution contains an image
                                var solution = response.solution;
                                var solutionText = $('#solutionText');
                                
                                if ($(solution).find('img').length > 0) {
                                    // If the solution contains an image, render only the image
                                    var imgTag = $(solution).find('img');
                                    solutionText.html(imgTag);
                                } else {
                                    // If no image, render the text inside the <p> tag or other tags
                                    var textContent = $(solution).text(); // Extract the text only
                                    solutionText.text(textContent);
                                }


                        if (response.is_correct) {
                            var checkMark = '<i class=\"fas fa-check correct\"></i>';
                            currentSlide.find('input[name=\"answers[' + questionId + ']\"]:checked').closest('.custom-radio').append(checkMark);
                            solutionDiv.hide();

                            setTimeout(function() {
                                $('.lesson_slides').slick('slickNext');
                                updateSlideIndex();
                            }, 500);

                           
                        } else {
                            var crossMark = '<i class=\"fas fa-times incorrect\"></i>';
                            currentSlide.find('input[name=\"answers[' + questionId + ']\"]:checked').closest('.custom-radio').append(crossMark);
                            currentSlide.find('.answer-option').prop('disabled', true);
                            solutionDiv.show();

                              

                            // Fetch and display the solution and explainations
                            
                             $('#explanationText').html(response.explanation);

                                // Check if the solution contains an image
                                var solution = response.solution;
                                var solutionText = $('#solutionText');
                                
                                if ($(solution).find('img').length > 0) {
                                    // If the solution contains an image, render only the image
                                    var imgTag = $(solution).find('img');
                                    solutionText.html(imgTag);
                                } else {
                                    // If no image, render the text inside the <p> tag or other tags
                                    var textContent = $(solution).text(); // Extract the text only
                                    solutionText.text(textContent);
                                }
                        }
                    } else {
                        console.error('Error:', response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error: ' + error);
                }
            });
        });

        // Handle 'Ok, Got it' button clicks in modals
        $('#modalNextSlideBtn').click(function() {
            $('.lesson_slides').slick('slickNext');
            $('#solutionModal').modal('hide');
            updateSlideIndex();
        });

        $('#nextSlideBtn').click(function() {
            $('.lesson_slides').slick('slickNext');
            $('#solutionDiv').hide();
            $('.quiz_slide').each(function() {
                $(this).find('.answer-option').prop('disabled', false);
            });
            updateSlideIndex();
        });

        // Update the current slide index and handle form submission
        function updateSlideIndex() {
            var currentSlideIndex = $('.lesson_slides').slick('slickCurrentSlide');
            $('#currentSlideIndex').val(currentSlideIndex);

            if (currentSlideIndex == totalSlides - 1 && !formSubmitting) {
                // On the last slide, calculate time spent and submit the form
                $('.answer-option').change(function() {
                    if (!formSubmitting) {
                        formSubmitting = true;
                        var endTime = new Date().getTime();
                        var timeSpent = Math.round((endTime - startTime) / 1000); // Time in seconds
                        $('#timeSpent').val(timeSpent); // Set the time spent value

                        var formData = $('#quizForm').serialize();
                        $.ajax({
                            url: $('#quizForm').attr('action'),
                            type: 'post',
                            data: formData,
                            success: function(response) {
                                console.log('Data inserted successfully');
                                window.location.href = '" . Yii::$app->urlManager->createUrl(['results-page']) . "';
                            },
                            error: function(xhr, status, error) {
                                console.error('AJAX Error: ' + error);
                            }
                        });
                    }
                });
            }
        }
    });
");
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
        updateSlideIndex(currentSlide + 1); // Since slick is 0-indexed

        var questionId = $('.slick-active .question_id').val();
        var selectedAnswer = $('.slick-active .answer_id').val();
           
        // $("#test1").hide();

        $.ajax({
        url:  "<?php echo Yii::$app->urlManager->createUrl(['lesson-content/check-answer']) ?>",
        type: 'post',
        data: { question_id: questionId, selectedAnswer: selectedAnswer },
        success: function(response) {
           
            if (response.status === 'success') {
                    console.log('Solution:', response.solution); 
                        
                        $('#explanationText').html(response.explanation);
                        
                        // Check if the solution contains an image
                        var solution = response.solution;
                        var solutionText = $('#solutionText');
                        
                        if ($(solution).find('img').length > 0) {
                            // If the solution contains an image, render only the image
                            var imgTag = $(solution).find('img');
                            solutionText.html(imgTag);
                        } else {
                            // If no image, render the text inside the <p> tag or other tags
                            var textContent = $(solution).text(); // Extract the text only
                            solutionText.text(textContent);
                        }
                        // $("#test1").show();
                        
            }
        }
    });


    });

    
    function updateSlideIndex(currentIndex) {
        $('#current-question-index').text(currentIndex); // Update the index display
    }   


      // Attach a click event listener to the #test1 element
    //   $("#test1").on("click", function() {
    //         // Copy the HTML content from #solutionDiv to .explanations
    //         $(".explanations").html($("#solutionDiv").html());
    //         solution_count++;
        
            
    //         if(solution_count > 5){
    //             // alert(solution_count);
    //             $("#modalNextSlideBtn").hide();
    //             $(".explanations").html("You have consumed all chances for using help.");
    //         }

    //    });

        var lessonId = <?= $lesson_id ?>;
        var modalOpenCountKey = 'solutionModalCount_' + lessonId;
        var modalOpenCount = localStorage.getItem(modalOpenCountKey) ? parseInt(localStorage.getItem(modalOpenCountKey)) : 0;
        var modalOpenLimit = 3; // Limit the modal opening to 3 times per lesson

        // When the Solution(Hint) button is clicked
        $('#test1').on('click', function(e) {
            e.preventDefault();
            
            // Check if the modal open count exceeds the limit
            if (modalOpenCount >= modalOpenLimit) {
                // Change the modal content and show the Close button
                $('#solutionModal .modal-body').html(`
                    <p>You have reached the limit of 3 Hint views for this lesson.</p>
                    <button type="button" class="theme_btn gotid_btn" id="closeModalBtn">Close</button>
                `);
                $('#solutionModal').modal('show');

                // Attach event to Close button to hide the modal
                $('#closeModalBtn').on('click', function() {
                    $('#solutionModal').modal('hide');
                });
                return false;
            }
        
            // Increment the count and store it in localStorage
            modalOpenCount++;
            localStorage.setItem(modalOpenCountKey, modalOpenCount);

             
            
            // Allow the modal to open with the actual solution content and add a new button
            $('#solutionModal .modal-body').html($('#solutionDiv').html() + '<button id="modalNextSlideBtn" class="theme_btn gotid_btn2">Ok Got it</button>'); 

            $('#solutionModal').modal('show');

            // Add click event listener for the new button
            $('#modalNextSlideBtn').click(function() {
                $('.lesson_slides').slick('slickNext');
            $('#solutionModal').modal('hide');
            updateSlideIndex();
            });
        });

        // Handle 'Ok, Got it' button click in the modal
        $('#modalNextSlideBtn').click(function() {
            $('.lesson_slides').slick('slickNext');
            $('#solutionModal').modal('hide');
            updateSlideIndex();
        });



     



});


</script>



<!-- return back on same slide of test functionality after tutorial button press -->

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
    
    // Agar quiz complete ho chuki hai, to ignore saved slide index aur start from 0
    if (localStorage.getItem(quizCompletedKey) === "true") {
      console.log("Quiz is completed. Starting from slide 0.");
      localStorage.removeItem(storageKey); // Saved index clear kar dein
      localStorage.removeItem(quizCompletedKey); // Flag bhi clear kar dein
    }
    
    var savedSlide = localStorage.getItem(storageKey);
    if (savedSlide === null) {
      savedSlide = 0;
    } else {
      savedSlide = parseInt(savedSlide, 10);
    }
    
    // Ensure savedSlide is within range of total slides
    var totalSlides = $('.quiz_slide').length;
    if (savedSlide >= totalSlides) {
      savedSlide = 0;
    }
    
    console.log("Starting Slide Index:", savedSlide);
    
    // Initialize the Slick slider with the saved slide index (or 0 if quiz completed)
    $('.lesson_slides').slick({
      initialSlide: savedSlide,
      infinite: false,
      slidesToShow: 1,
      slidesToScroll: 1
    });
    
    // Navigate immediately to the saved slide (as a precaution)
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
  // Aapka existing function jo current question/slide save karta hai (agar yeh pehle se defined hai)
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

  // Naya combined function: pehle limit check karega, phir index save karega
  function handleTutorialClick(event) {
    var tutorialKey = "tutorialPressCount_<?= $lesson_id ?>";
    var pressCount = parseInt(localStorage.getItem(tutorialKey) || "0", 10);
    console.log("Tutorial button pressed count:", pressCount);
    
    if (pressCount >= 3) {
      alert("You have exceeded the tutorial limit (3 times). You cannot access the tutorial again.");
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