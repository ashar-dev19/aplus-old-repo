<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.1.0/introjs.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.1.0/intro.min.js"></script>


<div class="my_breadcrumb">
    <?php
        $this->title = $model->full_name;
        $this->params['breadcrumbs'][] = ['label' => Yii::t('frontend', 'Student'), 'url' => ['index']];
        $this->params['breadcrumbs'][] = $this->title;
    ?>                  
</div>


<div class="notice">
  <div class="container-fluid">
 <p>We have updated the program and included many new features and a new look to the program. 
    You will see prompts on how to navigate, 
    but if you still have some issues, please feel free to email us at <a href="mailto:admin@aplustudents.com">admin@aplustudents.com</a>.</p>
  </div>
 
</div>
 
<!-- script check krna ho ke konsa timezone -->
<?php if (!empty($tzDebug)): ?>
<script>
(function(){
  const dbg = <?= json_encode($tzDebug, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) ?>;
  console.group('%cStudent Timezone Debug','color:#4CAF50;font-weight:bold;');
  console.table(dbg);
  console.log('clientBrowserTimeZone:', Intl.DateTimeFormat().resolvedOptions().timeZone);
  console.groupEnd();
})();
</script>
<?php endif; ?>



<!-- overlay (rehne do) -->
<div id="search-dim" aria-hidden="true"></div>





<div class="profile_detail streak_bar">
 
 <?php if (Yii::$app->session->hasFlash('success')): ?>
   
   <!-- Fireworks animation canvas -->
   <canvas id="fireworks_canvas"></canvas>
    <div class="center-message" style="text-align: center; margin-top: 20px;">
       <h2 id="congratsMessage">You have maintained a streak for 5 days! Great job!</h2>
       <button id="hideAnimationBtn" class="theme_btn" style="border:unset; margin: 0 auto;margin-top: 30px;">Hide Animation</button>
       
   
    
   </div>
 <?php endif; ?>


<div class="container">

       
      <div id="tutorialOverlay" class="tutorial-overlay">
            <div id="tutorialBox" class="tutorial-box">
                <p id="tutorialText"></p>
                
                <div class="wiz_buttons">
                  <button id="prevBtn" style="display: none;">Previous</button>
                  <button id="nextBtn" class="next">Next</button>
                </div>
                <div class="wiz_buttons">
                  <!-- final step buttons -->
                  <button id="disableTutorialBtn" style="display: none;">Don't show again</button>
                  <button id="remindLaterBtn" style="display: none;" class="next">OK, got it</button>
                </div>

              </div>
       </div>

    

 


 <div class="row main_row">

   <div class="col-md-12 col-md-12 col-lg-8 left_col"><div class="search_form">
    <div class="">
      <label for="lesson-search" style="font-weight:600;">Search Lesson</label>

      <!-- YouTube-like pill without right button -->
      <div class="yt-search-wrap" style="position:relative;max-width:720px;">
        <!-- left icon -->
        <span class="yt-search-icon" aria-hidden="true">
          <svg viewBox="0 0 24 24" width="20" height="20">
            <path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
          </svg>
        </span>

        <!-- input id SAME -->
        
        <input
          type="text"
          id="lesson-search"
          class="form-control yt-search-input"
          placeholder="Type at least 2 letters…"
          autocomplete="off"
          autocorrect="off"
          autocapitalize="off"
          spellcheck="false"
          inputmode="search"
        />

        <!-- suggestions box id SAME -->
        <div id="lesson-suggest-box" class="yt-suggest-box"
             style="position:absolute;top:100%;left:0;right:0;z-index:1000;display:none;background:#fff;border:1px solid #ddd;border-top:none;border-radius:0 0 12px 12px;max-height:260px;overflow:auto;">
        </div>
      </div>
    </div>
  </div>

                <div class="boxes subjects_box">
                    <?php if (!empty($subjects)): ?>
                        <?php foreach ($subjects as $subject): ?>
                            <a href="<?= Url::to(['grade/grades', 'id' => $model->id, 'subjectid' => $subject->id]) ?>" 
                                class="box" style="background-image: url('<?= Url::to('@web/' . $subject->image) ?>');">
                             </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No subjects found.</p>
                    <?php endif; ?>

                </div>

                <div class="boxes">
                    <a href="https://www.aplusclasses.ca/" class="box classes_box" style="background-image: url('/images/classes.jpeg');" target="_blank">
                        
                    </a>
                    <a href="https://beta.aplustudents.com/reports/progress-report" class="box reports_box" style="background-image: url('/images/reports_image.jpeg');">
                        Reports
                    </a>
                </div> 
            </div>

            <div class="col-md-12 col-md-12 col-lg-4 right_col">
                
                <div class="grow_card">
                    <h5 class="heading_text">Streak</h5>
                    <div class="content streak_box">
                        <!-- <h4>This Week</h4> -->
                        <p class="current"><span><?= Html::encode($continuousDaysCount) ?></span> of 5 days</p>
                        <div class="days">
                            <?php
                           $daysOfWeek = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                            $weekStart = $weekStartTzStr ?? date('Y-m-d');  // fallback just in case

                            $datesRange = [];
                            for ($i = 0; $i < 7; $i++) {
                                $datesRange[] = date('Y-m-d', strtotime($weekStart . " +$i days"));
                            }


                            foreach ($datesRange as $date):
                                $dayName = $daysOfWeek[date('N', strtotime($date)) - 1];
                                $isPresent = in_array($date, $attemptDates);
                            ?>
                                <div class="day <?= $isPresent ? 'present' : '' ?>">
                                    <?= Html::encode($dayName) ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <!-- <p class="attempt_para">500 bonus points for a 5 day streak</p> -->
                        <?php if ((int)$continuousDaysCount >= 5): ?>
                          <div class="streak-reward earned" role="status" aria-live="polite">
                            <span class="fire">🔥</span>
                            <span class="text"><b>+500</b> bonus points — 5-day streak champion!</span>
                            <span class="fire">🔥</span>
                          </div>
                        <?php else: ?>
                          <div class="streak-reward" role="status" aria-live="polite">
                            <span class="fire">🔥</span>
                            <span class="text">
                              Keep it up! <b><?= 5 - (int)$continuousDaysCount ?></b>
                              day<?= (5 - (int)$continuousDaysCount) === 1 ? '' : 's' ?> to go for <b>+500</b> bonus points
                            </span>
                            <span class="fire">🔥</span>
                          </div>
                        <?php endif; ?>

                    </div>
                    <hr>
                    <div class="content">
                        <h4>Total Points </h4>
                        
                        <p class="current"><span><?= Html::encode($totalPoints) ?></span></p>

             


                        <!-- <p class="current"><span>0</span> Weeks in a row</p> -->
                    </div>
                </div>
                <!-- <a href="#" class="view_btn">View Achievements <i class="fa fa-chevron-right"></i></a>         -->
            </div>
        </div>
    </div>
</div>


 

<!-- Animation Container -->
<script type="text/javascript">

    // Stop Animation and Hide Message
  

    const hideBtn = document.getElementById('hideAnimationBtn');
if (hideBtn) {
  hideBtn.addEventListener('click', function () {
    window.cancelAnimationFrame(loop);
    const cvs = document.getElementById('fireworks_canvas');
    if (cvs) cvs.style.display = 'none';
    const msg = document.getElementById('congratsMessage');
    if (msg) msg.style.display = 'none';
    this.style.display = 'none';
  });
}




   // Helper functions
  const PI2 = Math.PI * 2;
  const random = (min, max) => Math.random() * (max - min + 1) + min | 0;
  const timestamp = (_) => new Date().getTime();

  // Container
  class Confetti {
    constructor() {
      this.resize();
      this.fireworks = [];
      this.counter = 0;
    }

    resize() {
      this.width = canvas.width = window.innerWidth;
      let center = (this.width / 2) | 0;
      this.spawnA = (center - center / 4) | 0;
      this.spawnB = (center + center / 4) | 0;
      this.height = canvas.height = window.innerHeight;
      this.spawnC = this.height * 0.1;
      this.spawnD = this.height * 0.5;
    }

    onClick(x, y) {
      let count = random(3, 10);
      for (let i = 0; i < count; i++) {
        this.fireworks.push(
          new Firework(
            random(this.spawnA, this.spawnB),
            this.height,
            x,
            y,
            random(0, 260),
            random(30, 110)
          )
        );
      }
      this.counter = -1;
    }

    update(delta) {
      ctx.globalCompositeOperation = "hard-light";
      ctx.fillStyle = `rgba(0, 0, 0, 0.02)`;
      ctx.fillRect(0, 0, this.width, this.height);
      ctx.globalCompositeOperation = "lighter";

      for (let firework of this.fireworks) firework.update(delta);
      this.counter += delta * 3;

      if (this.counter >= 1) {
        this.fireworks.push(
          new Firework(
            random(this.spawnA, this.spawnB),
            this.height,
            random(0, this.width),
            random(this.spawnC, this.spawnD),
            random(0, 360),
            random(30, 110)
          )
        );
        this.counter = 0;
      }

      if (this.fireworks.length > 1000)
        this.fireworks = this.fireworks.filter((firework) => !firework.dead);
    }
  }

  class Firework {
    constructor(x, y, targetX, targetY, shade, offsprings) {
      this.dead = false;
      this.offsprings = offsprings;
      this.x = x;
      this.y = y;
      this.targetX = targetX;
      this.targetY = targetY;
      this.shade = shade;
      this.history = [];
    }

    update(delta) {
      if (this.dead) return;

      let xDiff = this.targetX - this.x;
      let yDiff = this.targetY - this.y;
      if (Math.abs(xDiff) > 1 || Math.abs(yDiff) > 1) {
        this.x += xDiff / 10;
        this.y += yDiff / 10;
      } else {
        this.dead = true;
        for (let i = 0; i < this.offsprings; i++) {
          this.history.push(
            new Particle(this.x, this.y, random(0, 360))
          );
        }
      }

      for (let particle of this.history) {
        if (!particle.dead) particle.update();
      }

      ctx.fillStyle = `hsl(${this.shade}, 100%, 50%)`;
      ctx.beginPath();
      ctx.arc(this.x, this.y, 4, 0, PI2);
      ctx.fill();
    }
  }

  class Particle {
    constructor(x, y, shade) {
      this.x = x;
      this.y = y;
      this.size = random(1, 4);
      this.shade = shade;
      this.dead = false;
      this.speed = 0;
      this.angle = Math.random() * PI2;
      this.velocityX = Math.cos(this.angle) * this.size;
      this.velocityY = Math.sin(this.angle) * this.size;
      this.gravity = 0.1;
    }

    update() {
      if (this.dead) return;

      this.velocityY += this.gravity;
      this.x += this.velocityX;
      this.y += this.velocityY;
      this.size -= 0.1;
      this.dead = this.size < 0;
      ctx.fillStyle = `hsl(${this.shade}, 100%, 50%)`;
      ctx.beginPath();
      ctx.arc(this.x, this.y, this.size, 0, PI2);
      ctx.fill();
    }
  }

  // Main loop
  const canvas = document.getElementById("fireworks_canvas");
  const ctx = canvas.getContext("2d");
  const confetti = new Confetti();
  let loop;

  const animate = (timestamp) => {
    confetti.update(timestamp);
    loop = requestAnimationFrame(animate);
  };

  if (document.getElementById('fireworks_canvas')) {
    animate(timestamp());
    canvas.addEventListener("click", (e) => {
      confetti.onClick(e.clientX, e.clientY);
    });

    window.addEventListener("resize", () => {
      confetti.resize();
    });
  }
</script>





<!-- setup wizard -->
 <style>

 
.search_form_parent{
  background-color: #F8F8F8;
}

 
#search-dim{
  position: fixed;
  inset: 0;
  background: rgb(0 0 0 / 68%);
  opacity: 0;
  pointer-events: none;
  transition: opacity .18s ease;
  z-index: 950;
}
#search-dim.active{
  opacity: 1;
  pointer-events: auto;
}
 
.yt-search-wrap{ width:100%; }
.yt-search-input{
  height:44px;
  border-radius:22px;
  padding-left:44px;      
  padding-right:14px;     
  border:1px solid #dfe1e5;
  box-shadow:none;
  transition: box-shadow .15s ease, border-color .15s ease;
}
.yt-search-input:focus{
  border-color:#c7ccd1;
  box-shadow: 0 1px 2px rgba(0,0,0,.05), 0 0 0 3px rgba(26,115,232,.12);
}
.yt-search-icon{
  position:absolute; left:12px; top:50%; transform:translateY(-50%);
  display:flex; align-items:center; justify-content:center;
  width:24px; height:24px; opacity:.65;
}
 
.yt-suggest-box{ border-top-left-radius:0 !important; }


 

  .streak-reward{
  display:flex; align-items:center; justify-content:center; gap:.5rem;
  background: linear-gradient(90deg,#fff6cc,#ffeaa7,#fff6cc);
  border:2px dashed #ffb703; border-radius:14px;
  padding:.55rem .9rem; margin-top:1.5rem;
  position:relative; font-weight:600;
}
.streak-reward .text{letter-spacing:.2px}
.streak-reward .text b{color:#f97316; position:relative}
.streak-reward .fire{font-size:1.15rem; animation:wiggle .8s infinite ease-in-out}

.streak-reward.earned{
  box-shadow:0 8px 20px rgba(249,115,22,.15);
  border-style:solid;
}
.streak-reward .text b::after{
  content:"✨";
  position:absolute; right:-1rem; top:-.7rem;
  animation:twinkle 1.5s infinite;
}

.streak-reward::after{
  content:"🏆";
  position:absolute; right:.5rem; top:-.8rem;
  font-size:1.05rem; animation:pop 2s infinite;
}

@keyframes wiggle{0%,100%{transform:translateY(0) rotate(0)}50%{transform:translateY(-3px) rotate(-6deg)}}
@keyframes twinkle{0%,100%{opacity:0; transform:scale(.3) rotate(0)}50%{opacity:1; transform:scale(1) rotate(20deg)}}
@keyframes pop{0%,80%,100%{transform:scale(0); opacity:0}20%{transform:scale(1); opacity:1}}



  .tutorial-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
    display: none;
  }

  .tutorial-box::before {
    content: "";
    position: absolute;
    border-style: solid;
    border-width: 10px;
    border-color: transparent transparent white transparent;
    display: none; /* Initially hidden */
    }
    .tutorial-box {
    position: absolute;
    z-index: 99999;
    background: white;
    padding: 20px;
    border-radius: 8px;
    text-align: center;
    width: 300px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
 }
 .tutorial-box p{
  font-style:italic;
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

  .search_form{
        margin: 0 auto;
        width: 50%;
    margin-bottom: 29px;
  }
  .search_form label{
    text-align: center;
    display: block;
    background-color: var(--theme1);
    text-align: center;
    color: #fff;
    padding: 12px 30px;
    margin-bottom: 10px;
    text-transform: uppercase;
  }

  @media (max-width:767px){
    .search_form {
       margin: 0 auto;
        width: 91%;
    }
  }
 </style>


<script>
(function($){
  const $input  = $("#lesson-search");
  const $box    = $("#lesson-suggest-box");
  const $dim    = $("#search-dim");

  const SUGGEST = "<?= \yii\helpers\Url::to(['student/lesson-suggest']) ?>";
  const INFOURL = "<?= \yii\helpers\Url::to(['student/lesson-info']) ?>";

  let t = null;

  function hideBox(){
    $box.hide().empty();
    $dim.removeClass('active');
  }

  function render(items){
    if (!items || !items.length){ hideBox(); return; }
    let html = '<ul class="list-group" style="margin:0">';
    items.forEach(it=>{
      html += `<li class="list-group-item lesson-suggest-item"
                 data-id="${it.id}"
                 data-title="${(it.title || '').replace(/"/g,'&quot;')}"
                 style="cursor:pointer">${it.title}</li>`;
    });
    html += '</ul>';
    $box.html(html).show();
    $dim.addClass('active');
  }

  function searchNow(){
    const q = $input.val().trim();
    if (q.length < 2){ hideBox(); return; }
    $.getJSON(SUGGEST, {q})
      .done(res => render(res && res.items ? res.items : []))
      .fail(() => hideBox());
  }

  // typing debounce
  $input.on('input', function(){
    clearTimeout(t);
    t = setTimeout(searchNow, 250);
  });

  // Enter key -> search
  $input.on('keydown', function(e){
    if (e.key === 'Enter'){
      e.preventDefault();
      searchNow();
    }
  });

  // choose from suggestions
  $box.on('click', '.lesson-suggest-item', function(){
    const title = $(this).data('title') || $(this).text().trim();
    window.location.href = INFOURL + '?title=' + encodeURIComponent(title);
  });

  // outside click / overlay / escape close
  $(document).on('click', function(e){
    if (!$(e.target).closest('.yt-search-wrap, #lesson-suggest-box').length){
      hideBox();
    }
  });
  $dim.on('click', hideBox);
  $(document).on('keydown', function(e){
    if (e.key === 'Escape') hideBox();
  });
})(jQuery);
</script>





<!-- tool tip script -->
<!-- <script>
document.addEventListener("DOMContentLoaded", function () {
    let tutorialSteps = [
      { selector: ".subjects_box", text: "Select a subject: Math or English." },
      { selector: ".classes_box", text: "Click here to go to live classes." },
      { selector: ".reports_box", text: "Click here to see progress reports." },
      { selector: ".streak_box", text: "Complete 5 days in a week for a streak & 500 bonus points. Skip any 2 days and still maintain the weekly streak." },
      { 
          selector: ".avator", text: "Click on your profile name or picture to change user. Click on logout to sign out."
      },
      { selector: null, text: "Setup Complete!", isFinal: true }
  ];


    let currentStep = 0;

    function startTutorial() {
        if (localStorage.getItem("tutorialDisabled")) return;
        document.getElementById("tutorialOverlay").style.display = "flex";
        showStep();
    }

    function showStep() {
        let step = tutorialSteps[currentStep];
        let tutorialBox = document.getElementById("tutorialBox");
        let tutorialText = document.getElementById("tutorialText");

        if (step.isFinal) {
            tutorialText.innerText = step.text;
            document.getElementById("nextBtn").style.display = "none";
            document.getElementById("prevBtn").style.display = "none";

            document.getElementById("disableTutorialBtn").style.display = "inline-block";
            document.getElementById("remindLaterBtn").style.display = "inline-block";
        } else {
            tutorialText.innerText = step.text;

            document.getElementById("nextBtn").style.display = currentStep < tutorialSteps.length - 1 ? "inline-block" : "none";
            document.getElementById("prevBtn").style.display = currentStep > 0 ? "inline-block" : "none";
            
            document.getElementById("disableTutorialBtn").style.display = "none";
            document.getElementById("remindLaterBtn").style.display = "none";

            let element = document.querySelector(step.selector);
            
            if (element) {
                let rect = element.getBoundingClientRect();
                const offset = 10;
              const boxHeight = 160; // Estimated height of the box

              let top = rect.top + window.scrollY + rect.height + offset;

              // If box would go below viewport, show it above the element
              if (top + boxHeight > window.innerHeight + window.scrollY) {
                  top = rect.top + window.scrollY - boxHeight - offset;
              }

              tutorialBox.style.top = `${top}px`;
              tutorialBox.style.left = `${rect.left + window.scrollX}px`;


                element.classList.add("tutorial-highlight");
            }
        }
    }

    function nextStep() {
        let previousElement = document.querySelector(tutorialSteps[currentStep]?.selector);
        if (previousElement) previousElement.classList.remove("tutorial-highlight");

        currentStep++;
        if (currentStep < tutorialSteps.length) {
            showStep();
        }
    }

    function prevStep() {
        let previousElement = document.querySelector(tutorialSteps[currentStep]?.selector);
        if (previousElement) previousElement.classList.remove("tutorial-highlight");

        if (currentStep > 0) {
            currentStep--;
            showStep();
        }
    }

    function endTutorial() {
        document.getElementById("tutorialOverlay").style.display = "none";
        document.querySelectorAll(".tutorial-highlight").forEach(el => el.classList.remove("tutorial-highlight"));
    }

    function disableTutorial() {
        localStorage.setItem("tutorialDisabled", "true");
        endTutorial();
    }

    function remindLater() {
        localStorage.removeItem("tutorialDisabled");
        endTutorial();
    }

    document.getElementById("nextBtn").addEventListener("click", nextStep);
    document.getElementById("prevBtn").addEventListener("click", prevStep);
    document.getElementById("disableTutorialBtn").addEventListener("click", disableTutorial);
    document.getElementById("remindLaterBtn").addEventListener("click", remindLater);

    document.getElementById("tutorialOverlay").addEventListener("click", function (e) {
      if (e.target.id === "tutorialOverlay") {
          endTutorial();
      }
    });

    setTimeout(startTutorial, 2000);
});

</script> -->

<!-- localStorage.removeItem("tutorialDisabled"); -->
<!-- location.reload(); -->
