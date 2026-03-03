<?php

/**

 * @var yii\web\View $this

 * @var yii\data\ActiveDataProvider $dataProvider

 * @var frontend\models\search\ArticleSearch $searchModel

 * @var array $categories

 * @var array $archive

 */



$title = $this->title = Yii::t('frontend', 'Current Students');


?>

 

<!-- <link href="https://unpkg.com/intro.js/minified/introjs.min.css" rel="stylesheet">
<script src="https://unpkg.com/intro.js/minified/intro.min.js"></script> -->
 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.1.0/introjs.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.1.0/intro.min.js"></script>


 
 <div class="notice">
  <div class="container-fluid">
 <p>We have updated the program and included many new features and a new look to the program. 
    You will see prompts on how to navigate, 
    but if you still have some issues, please feel free to email us at <a href="mailto:admin@aplustudents.com">admin@aplustudents.com</a>.</p>
  </div>
 
</div>
 

<div class="select_profile">
  
    
    <div class="container">
    <!-- <h1>Select Student?</h1> -->
     <div class="row">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
               
                <?= \yii\widgets\ListView::widget([
                       'dataProvider' => $dataProvider,
                        'pager' => [
                            'hideOnSinglePage' => true,
                        ],

                        'itemView' => '_item', // Make sure you have this view defined
                         
                        'layout' => '{items}',

                    ]) ?>

                             

                 <div class="pagi">

                      <?= $this->render('_breadcrumb_pagination', ['dataProvider' => $dataProvider]) ?>

                </div>
            </div>
            <!-- <div class="col-12 col-sm-12 col-md-3 col-lg-3 last_col">
                <div class="box">
                    <div class="plus">
                            <a href="/student/create" class="icon"><i class="fa fa-plus"></i></a>
                        </div>
                        <a href="/student/create" class="add">Add Profile</a>
                                          
                </div>
            </div> -->
      </div>

    </div>



</div>

 <!-- tool tip script -->
<!-- <script>
(function () {
  // use a unique key for this page so it doesn’t interfere with others
  const KEY = 'wizardDismissed_current';
  if (localStorage.getItem(KEY)) return;

  const intro = introJs();
  intro.setOptions({
    steps: [
      { element: document.querySelector('.list-view'),
        intro: 'Click on a user to continue.', position: 'bottom' },
      { element: document.querySelector('.card'),
        intro: 'Change profile picture or see reports here.', position: 'bottom' },
      // last step (no element)
      { intro: 'Setup Complete!', position: 'bottom', disableInteraction: true }
    ],
    showStepNumbers: false,
    showBullets: false,
    overlayOpacity: 0.6,
    exitOnOverlayClick: true,
    showButtons: true,       // keep footer so we can replace its content
    nextLabel: 'Next',
    prevLabel: 'Back'
  });

  function patchFooterIfLast() {
    // works on Intro.js v7 — use _introItems length for safety
    const isLast = intro._currentStep === intro._introItems.length - 1;
    const footer = document.querySelector('.introjs-tooltipbuttons');
    if (!footer) return;

    if (isLast) {
      // wipe default Prev/Next/Done
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
  intro.onchange(patchFooterIfLast); // some themes need both

  // start when DOM targets exist
  setTimeout(function () {
    if (document.querySelector('.list-view') && document.querySelector('.card')) {
      intro.start();
    }
  }, 500);
})();
</script> -->

<style>
  .custom-gray { background:#ddd !important; color:#000 !important; margin-right:10px !important; }
</style>

<!-- agr hum reset karna chahe wizard ko -->
<!-- localStorage.removeItem("wizardDismissed"); -->
<!-- location.reload(); -->


<style>
 
  
.introjs-overlay {
    background: rgba(0, 0, 0, 0.6) !important;
    position: fixed !important;
    top: 0;
    left: 0;
    width: 100% !important;
    height: 100% !important;
    opacity: 1 !important;
    z-index: 99998 !important;
}

   
.introjs-helperLayer {
    box-shadow: 0 0 0 3px yellow !important;
    border-radius: 5px;
}
.introjs-tooltiptext {
    font-size: 14px;
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
    text-shadow: unset;
}
.introjs-button:hover {
    background-color: #17a093 !important;
}
.custom-gray {
    background: #ddd !important;
    color: #000 !important;
    margin-right: 10px !important;
}
.custom-gray:hover{
     color: #ffffffff !important;
}

/* Always show buttons unless we manually hide */
/* .introjs-tooltipbuttons {
    display: flex !important;
    justify-content: flex-end;
    flex-wrap: wrap;
    margin-top: 10px;
} */

.introjs-tooltip.introjs-custom-final .introjs-prevbutton,
.introjs-tooltip.introjs-custom-final .introjs-nextbutton,
.introjs-tooltip.introjs-custom-final .introjs-donebutton {
    display: none !important;
}



 </style>