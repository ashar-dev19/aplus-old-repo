<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\LessonContent */

?>
<div class="top_bar_tuts">
    <div class="inner">
        <div>
            <a href="javascript:void(0);" onclick="goBack()" class="close_btn"><i class="fas fa-times"></i></a>
        </div>
        <div>
            <!-- Trigger modal on Test button click -->
            <?= Html::a('Test', '#exampleModal', ['class' => 'test_btn theme_btn', 'data-bs-toggle' => 'modal']) ?>
        </div>
    </div>
</div>

<div class="topic_page tutorials_page">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-12">
                <!-- Custom arrow buttons -->
                <div class="slick_nav">
                    <div class="left">
                        <button class="custom-prev-arrow slick_btn"><i class="fa fa-arrow-left"></i> Previous</button>
                    </div>
                    <div class="right">
                        <button class="custom-next-arrow slick_btn">Next <i class="fa fa-arrow-right"></i></button>
                    </div>
                </div>

                <?php
                echo "<div class='lesson_slides'>";
                foreach ($lessonContent as $content) {
                    
                    // Clean up the title by removing HTML tags and non-breaking spaces
                    $cleanTitle = trim(strip_tags(str_replace('&nbsp;', ' ', $content->title)));
                    
                    

                    echo "<div class='parent'>";
                    echo "<div class='content_box'>";

                    echo "<div class='content'>";
                    echo "<h2 class='heading'>". Html::encode($cleanTitle) . "<h2/>";
                    echo "</div>";

                    // Extract image path from the content
                    $pattern = '/https?:\/\/\S+\.(?:png|jpg|jpeg|gif)/i';
                    preg_match($pattern, $content->content, $matches);

                    if (!empty($matches)) {
                        $imagePath = $matches[0];
                        echo "<img src='$imagePath' alt='Image'><br/>";
                    }

                    // Display the remaining content
                    $contentWithoutImage = preg_replace($pattern, '', $content->content);
                    echo "<div class='answer'>";
                         echo $contentWithoutImage;
                    echo "</div>";

                    // Display the explanations
                    if (!empty($content->explanations)) {
                        echo "<div class='explanations'>";
                        foreach ($content->explanations as $explanation) {
                            echo "<div class='explanation'>";
                            echo "<p>" . \yii\helpers\Html::decode($explanation->explanation) . "</p>";
                            echo "</div>";
                        }
                        echo "</div>";
                    }

                    echo "</div>";
                    echo "</div>";
                }
                echo "</div>";
                ?>

            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="btns">
                    <a href="/category" class="theme_btn"><i class="fa fa-chevron-left"></i> My Study room </a>
                </div>
            </div>
        </div>
    </div>

    
   <!-- Bootstrap Modal -->
   <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <!-- Initially hide the start test link -->
                    <div id="startTestLink" style="display: none;">
                        <h2>Your test is ready</h2>
                        <!-- Pass lesson_id to actionTest -->
                        <?= Html::a('Start Test', ['test', 'lesson_id' => $lesson_id], ['class' => 'test_btn theme_btn']) ?>
                        
                    </div>
                    <!-- Show this when the modal opens -->
                    <div id="modalContent">
                        <!-- Loading spinner or any other content while waiting for the modal to open -->
                        <h2>Loading...</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<?php
// JavaScript to change modal content when modal is opened
$this->registerJs("
    $('#exampleModal').on('shown.bs.modal', function () {
        // Simulate a delay or perform an AJAX request to load content
        setTimeout(function() {
            $('#modalContent').html('');
            $('#startTestLink').show();
        }, 1000); // Adjust the delay as needed
    });
");
?>
