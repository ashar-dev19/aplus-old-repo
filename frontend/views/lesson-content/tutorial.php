<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\helpers\HtmlPurifier;

/* @var $this yii\web\View */
/* @var $model frontend\models\LessonContent */
/* @var $existingAttempt frontend\models\LessonTestAttempt */



/**
 * URL ko iframe me convert karta hai (YouTube/Vimeo/Google Drive).
 */
function renderVideoFromUrl(?string $url): string {
    if (!$url) return '';
    $url = trim($url);

    // YouTube
    if (preg_match('~(?:youtube\.com/watch\?v=|youtu\.be/)([A-Za-z0-9_-]{11})~', $url, $m)) {
        $src = "https://www.youtube.com/embed/{$m[1]}";
        return "<div class='video-wrap'><iframe src='{$src}' allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share' allowfullscreen></iframe></div>";
    }

    // Vimeo
    if (preg_match('~vimeo\.com/(\d+)~', $url, $m)) {
        $src = "https://player.vimeo.com/video/{$m[1]}";
        return "<div class='video-wrap'><iframe src='{$src}' allow='autoplay; fullscreen; picture-in-picture' allowfullscreen></iframe></div>";
    }

    // Google Drive
    if (preg_match('~drive\.google\.com/file/d/([^/]+)/~', $url, $m)) {
        $src = "https://drive.google.com/file/d/{$m[1]}/preview";
        return "<div class='video-wrap'><iframe src='{$src}' allow='autoplay' allowfullscreen></iframe></div>";
    }

    // Fallback: plain link
    return Html::a('Open video', $url, ['target'=>'_blank','rel'=>'noopener']);
}

/**
 * Hybrid: agar pure <iframe> diya ho to sanitized iframe render karo,
 * warna URL ko auto-embed karo.
 */
function renderVideoHybrid(?string $value): string {
    if (!$value) return '';
    $value = trim($value);

    // full iframe paste
    if (stripos($value, '<iframe') !== false) {
        return HtmlPurifier::process($value, [
            'HTML.SafeIframe'      => true,
            'URI.SafeIframeRegexp' => '%^(https?:)?//(www\.youtube\.com/embed/|player\.vimeo\.com/video/|drive\.google\.com/file/)%',
        ]);
    }

    // URL → iframe
    return renderVideoFromUrl($value);
}


?>


 



<div class="top_bar_tuts dnone_mob" >
    <div class="inner">
        <div>  
            <a href="<?= Url::to(['lesson/', 'id' => $lesson->chapter_id]) ?>" class="special_btn">
                <i class="fa fa-chevron-left"></i> <span>Lessons</span>
            </a> 
        </div>
        <div>
            <!-- <a href="https://www.youtube.com/channel/UCi_BNskNcmwdwX4abUBAu1g" target="_blank" class="vid_btn">Video Tutorial </a>  -->
            <!-- <a href="https://m.youtube.com/@aplusclasses1756/playlists " target="_blank" class="vid_btn">Video Tutorial </a>  -->
            <?php if (!empty($lesson->video_url)): ?>
                <a href="#videoModal" class="vid_btn" data-bs-toggle="modal">Video Tutorial</a>
            <?php endif; ?>

            
        </div>
        <div> 
            <?= Html::a('Test', '#exampleModal', ['class' => 'animated_btn', 'data-bs-toggle' => 'modal']) ?> 
        </div> 
    </div>
</div>

<div class="top_bar_tuts dnone_pc">
    <div class="inner">
        <div class="lessons_btn">  
            <a href="<?= Url::to(['lesson/', 'id' => $lesson->chapter_id]) ?>" class="special_btn">
                <i class="fa fa-chevron-left"></i> <span>Lessons</span>
            </a> 
        </div>
        
        <div class="test_btn"> 
            <?= Html::a('Test', '#exampleModal', ['class' => 'animated_btn', 'data-bs-toggle' => 'modal']) ?> 
        </div> 

        <div class="video_btn">
            <!-- <a href="https://www.youtube.com/channel/UCi_BNskNcmwdwX4abUBAu1g" target="_blank" class="vid_btn">Video Tutorial </a>   -->
            <?php if (!empty($lesson->video_url)): ?>
                <a href="#videoModal" class="vid_btn" data-bs-toggle="modal">Video Tutorial</a>
            <?php endif; ?>
        </div>
    </div>
</div>



<div class="topic_page tutorials_page lesson-<?= (int)$lesson_id ?>">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-12">
            <h4 class="lesson_name"><?php echo $lesson->title ?></h4>

            
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
                    echo "<h2 class='heading'>". $content->title. "<h2/>";
                    echo "</div>";

                    // Extract image path from the content
                    $pattern = '/https?:\/\/\S+\.(?:png|jpg|jpeg|gif)/i';
                    preg_match($pattern, $content->content, $matches);

                    if (!empty($matches)) {
                        $imagePath = $matches[0];
                        echo "<img src='$imagePath' alt='Image'><br/>";
                    }

                   
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

                     // Display the remaining content
                     $contentWithoutImage = preg_replace($pattern, '', $content->content);
                     echo "<div class='answer'>";
                         echo "<p>Answer is:</p>";
                          echo $contentWithoutImage;
                     echo "</div>";
 

                    echo "</div>";
                    echo "</div>";
                }
                echo "</div>";
                ?>

            </div>
        </div>
<!-- 
        <div class="row">
            <div class="col-md-12">
                <div class="btns">
                    <a href="/category" class="theme_btn"><i class="fa fa-chevron-left"></i> My Study room </a>
                </div>
            </div>
        </div> -->
    </div>

    
    <div id="tutorialOverlay" class="tutorial-overlay">
        <div id="tutorialBox" class="tutorial-box">
            <p id="tutorialText"></p>
            <div class="wiz_buttons">
                <button id="prevBtn" style="display: none;">Previous</button>
                <button id="nextBtn" class="next">Next</button>
            </div>
            <div class="wiz_buttons">
                <button id="disableTutorialBtn" style="display: none;">Do not show again</button>
                <button id="remindLaterBtn" style="display: none;" class="next">Remind me later</button>
            </div>
        </div>
    </div>




    
   <!-- Bootstrap Modal Test-->
   <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <!-- Initially hide the start test link -->
                    <div id="startTestLink" style="display: none;">
                        <h2>Let’s Begin!</h2>
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

     <!-- Bootstrap Modal Vidoe Playr-->
    <?php if (!empty($lesson->video_url)): ?>
    <div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLbl" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h5 id="videoModalLbl" class="modal-title">Video Tutorial</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <?= renderVideoHybrid($lesson->video_url) ?>
        </div>
        </div>
    </div>
    </div>
    <?php endif; ?>


</div>



<?php
$this->registerJs(<<<'JS'
(function(){
  var modal = document.getElementById('videoModal');
  if(!modal) return;

  // first time pe original src cache karo
  modal.addEventListener('show.bs.modal', function(){
    modal.querySelectorAll('iframe, video').forEach(function(el){
      if(el.tagName === 'IFRAME'){
        if(!el.dataset.origSrc){ el.dataset.origSrc = el.src; }
      } else if(el.tagName === 'VIDEO'){
        try { el.play(); } catch(e){}
      }
    });
  });

  // hide par playback hard stop
  modal.addEventListener('hide.bs.modal', function(){
    // YouTube/Vimeo/Drive iframes
    modal.querySelectorAll('iframe').forEach(function(ifr){
      if(!ifr.dataset.origSrc){ ifr.dataset.origSrc = ifr.src; }
      // iOS/Android par sure-stop
      try { ifr.src = 'about:blank'; } catch(e){}
    });
    // <video> tags (agar kabhi use hon)
    modal.querySelectorAll('video').forEach(function(v){ try { v.pause(); } catch(e){} });
  });

  // dobara open par src restore
  modal.addEventListener('shown.bs.modal', function(){
    modal.querySelectorAll('iframe').forEach(function(ifr){
      if((!ifr.src || ifr.src === 'about:blank') && ifr.dataset.origSrc){
        ifr.src = ifr.dataset.origSrc;
      }
    });
  });
})();
JS);


?>





<style>
.video-wrap{position:relative;padding-bottom:56.25%;height:0;overflow:hidden;border-radius:12px;box-shadow:0 4px 18px rgba(0,0,0,.08);margin:12px 0;}
.video-wrap iframe{position:absolute;top:0;left:0;width:100%;height:100%;border:0;}
 

 
.introjs-button {
    background: #1EB2A6!important;
    color: #fff!important;
    border: none!important;
    padding: 6px 12px!important;
    cursor: pointer!important;
   text-shadow: none !important;
}
.introjs-button:hover {
    background: #17a093!important;
}
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
 


  .tutorial-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.tutorial-box {
    position: fixed;
    z-index: 99999;
    background: white;
    padding: 20px;
    border-radius: 8px;
    text-align: center;
    width: 300px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
}

.tutorial-box p {
    font-style: italic;
}

.tutorial-box button {
    margin: 10px;
    padding: 8px 12px;
    background: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.tutorial-highlight {
    position: relative;
    z-index: 10000;
    border: 3px solid yellow !important;
    box-shadow: 0px 0px 10px yellow !important;
}






</style>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.1.0/introjs.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.1.0/intro.min.js"></script>



<!-- <script>
document.addEventListener("DOMContentLoaded", function () {
  // stop if user already dismissed
  if (localStorage.getItem("tutorialDisabled_tutorialsPage")) return;

  const intro = introJs();
  intro.setOptions({
    steps: [
      { element: document.querySelector(".special_btn"),
        intro: "Click here to go back to the lesson.",
        position: "bottom" },
      { element: document.querySelector(".vid_btn"),
        intro: "Click here to access video tutorials.",
        position: "bottom" },
      { element: document.querySelector(".animated_btn"),
        intro: "Click here to begin the test when ready.",
        position: "bottom" },
    //   { element: document.querySelector(".content_box"),
    //     intro: "Only 3 attempts allowed for hints/tutorials in a test.",
    //     position: "bottom" 
    //     },
      // final step (no element)
      { intro: "Setup Complete!", position: "bottom", disableInteraction: true }
    ],
    showButtons: true,            // keep default buttons for non-final steps
    showStepNumbers: false,
    showBullets: false,
    overlayOpacity: 0.6,
    exitOnOverlayClick: true,
    showSkipButton: false,
    nextLabel: "Next",
    prevLabel: "Back"
  });

  function renderFinalStepButtons() {
    const footer = document.querySelector(".introjs-tooltipbuttons");
    if (!footer) return;

    const isLast = intro._currentStep === intro._introItems.length - 1;

    if (isLast) {
      // replace default buttons with our two
      footer.innerHTML = "";

      const dont = document.createElement("button");
      dont.className = "introjs-button custom-gray";
      dont.textContent = "Don't show again";
      dont.onclick = function () {
        localStorage.setItem("tutorialDisabled_tutorialsPage", "true");
        intro.exit();
      };

      const ok = document.createElement("button");
      ok.className = "introjs-button";
      ok.textContent = "OK, got it";
      ok.onclick = function () { intro.exit(); };

      footer.appendChild(dont);
      footer.appendChild(ok);
    } else {
      // do nothing on other steps—let Intro keep Next/Back
    }
  }

  intro.onafterchange(renderFinalStepButtons);
  intro.onchange(renderFinalStepButtons);

  setTimeout(() => intro.start(), 1500);
});
</script> -->





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




<!-- Start of LiveAgent integration script: Chat button: Circle animated button 37 -->
<script type="text/javascript">
(function(d, src, c) { var t=d.scripts[d.scripts.length - 1],s=d.createElement('script');s.id='la_x2s6df8d';s.defer=true;s.src=src;s.onload=s.onreadystatechange=function(){var rs=this.readyState;if(rs&&(rs!='complete')&&(rs!='loaded')){return;}c(this);};t.parentElement.insertBefore(s,t.nextSibling);})(document,
'https://aplustudents.ladesk.com/scripts/track.js',
function(e){ LiveAgent.createButton('3s64radz', e); });
</script>
 
 
