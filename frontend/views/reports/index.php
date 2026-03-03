<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
use frontend\models\LessonContent;

 // Chapter chart labels/links prepare (server-side)
$chapterLabels = [];
$chapterCounts = [];
$chapterLinks  = [];
foreach ($chapterData as $chapterId => $data) {
    $chapterLabels[] = $data['title'];
    $chapterCounts[] = $data['count'];
    $chapterLinks[]  = 'https://beta.aplustudents.com/lesson?id=' . $chapterId;
}

$normalizeSubject = function($id, $title) {
    if ($id !== null && in_array((int)$id, [2, 5], true)) {
        return 'English';
    }
    $t = is_string($title) ? trim(mb_strtolower($title)) : '';
    if (in_array($t, ['language arts', 'english'], true)) {
        return 'English';
    }
    return $title ?: 'N/A';
};
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<?php
$this->registerCssFile('https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css');
$this->registerJsFile('https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js', ['depends' => [\yii\web\JqueryAsset::class]]);
?>
 


  <!-- TimeZone DEBUG (only when ?tzdebug=1 is present)  -->
 <?php if (!empty($tzDebug)): ?>
<script>
(function(){
  const dbg = <?php echo json_encode($tzDebug, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) ?>;

  console.group('%cReport Timezone Debug','color:#1EB2A6;font-weight:bold;');
  console.table(dbg);

  // Browser (client) timezone
  try {
    const clientTZ = Intl.DateTimeFormat().resolvedOptions().timeZone;
    console.log('clientBrowserTimeZone:', clientTZ);
  } catch(e){ console.warn('Intl TZ not supported?', e); }

  // Human checks for serverNow
  if (dbg.serverNow_epoch) {
    console.log('serverNow (client render):', new Date(dbg.serverNow_epoch * 1000).toString());
  }

  // Human checks for sample attempt (if provided)
  if (dbg.sampleAttempt_epoch) {
    console.log('sampleAttempt (client render):', new Date(dbg.sampleAttempt_epoch * 1000).toString());
    console.log('sampleAttempt (Yii formatter display TZ):', dbg.sampleAttempt_asDate_displayTZ);
  }

  console.groupEnd();
})();
</script>
<?php endif; ?>
  

<div class="reports_page">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md-12">
        <h2 class="heading">Progress Report</h2>

        <!-- Filters -->
        <div class="reports_filter_form">

            <form method="get" action="<?= Yii::$app->urlManager->createUrl(['reports/progress-report']) ?>" class="">
            <div class="form-group">
              <label for="student_id">Select Child</label>
              <div class="custom-select-wrapper">
                <select id="student_id" name="student_id" class="form-control custom-select">
                  <option value="">All</option>
                  <?php foreach ($students as $stu): ?>
                    <option value="<?= $stu->id ?>" <?= $selectedStudent == $stu->id ? 'selected' : '' ?>>
                      <?= Html::encode($stu->full_name) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label for="subject_id">Select Subject</label>
              <div class="custom-select-wrapper">
                <select id="subject_id" name="subject_id" class="form-control custom-select">
                  <option value="">All</option>
                  <?php foreach ($subjects as $sub): ?>
                    <?php $label = $normalizeSubject($sub->id, $sub->title); ?>
                    <option value="<?= $sub->id ?>" <?= $selectedSubject == $sub->id ? 'selected' : '' ?>>
                      <?= Html::encode($label) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label for="date">Select Date</label>
              <div class="custom-select-wrapper">
                <input type="text" id="date" name="date" class="form-control custom-select" placeholder="Select Date Range" value="<?= Html::encode($selectedDateRange) ?>" readonly>
              
             
                    <!-- Small cute radio group -->
             

                  <?php $qVal = Yii::$app->request->get('quick', ''); ?>
                  <div class="quick-radio-group">
                    <label class="qr-pill">
                      <input type="radio" name="quick" value="all"   <?= $qVal==='all'   ? 'checked' : '' ?>>
                      <span>All</span>
                    </label>
                    <label class="qr-pill">
                      <input type="radio" name="quick" value="day"   <?= $qVal==='day'   ? 'checked' : '' ?>>
                      <span>Today</span>
                    </label>
                    <label class="qr-pill">
                      <input type="radio" name="quick" value="week"  <?= $qVal==='week'  ? 'checked' : '' ?>>
                      <span>This Week</span>
                    </label>
                    <label class="qr-pill">
                      <input type="radio" name="quick" value="month" <?= $qVal==='month' ? 'checked' : '' ?>>
                      <span>This Month</span>
                    </label>
                  </div>



              </div>
            </div>

            <div class="form-group">
              <label for="performance">Select Performance</label>
              <div class="custom-select-wrapper">
                <select id="performance" name="performance" class="form-control custom-select">
                  <option value="">All</option>
                  <option value="Excellent" <?= $selectedPerformance == 'Excellent' ? 'selected' : '' ?>>Over 80% and above</option>
                  <option value="Average" <?= $selectedPerformance == 'Average' ? 'selected' : '' ?>>51% to 79%</option>
                  <option value="Requires Attention" <?= $selectedPerformance == 'Requires Attention' ? 'selected' : '' ?>>50% and Below</option>
                </select>
              </div>
            </div>

            

            <div class="filter_btns">
              <button type="submit" class="theme_btn filter_btn">Show</button>
            
              <a id="clearFilters" class="theme_btn clear_btn" href="<?= Yii::$app->urlManager->createUrl(['reports/progress-report']) ?>">Clear</a>

            </div>

            
         

          </form>

           

        </div>

        <!-- Summary boxes -->
        <div class="summary_boxes">
          <div class="inner">
            <div class="box box1">
              <div class="heading"><h4>Questions Answered</h4></div>
              <div class="details">
                <div class="total_count">
                  <img src="/images/checked2.png">
                  <p><?= $correctAnswers / 20 ?></p>
                </div>
              </div>
            </div>

            <div class="box box2">
              <div class="heading">
                <h4>Time Spent</h4>
                <p class="small_text">Choose a student to see the time spent.</p>
              </div>
              <div class="details">
                <div class="total_count">
                  <img src="/images/clock2.png">
                  <p><?= $totalTimeSpent ?></p>
                </div>
              </div>
              <p class="small_text">Understand how many hours and minutes were spent doing questions including tutorial</p>
            </div>

            <div class="box box3">
              <h4>Performance</h4>
              <canvas id="performanceChart"></canvas>
            </div>
          </div>

          <div class="inner2">
            <div class="box box4">
              <h4>Chapters practiced</h4>
              <canvas id="chapterChart"></canvas>
              <p class="small_text">Understand on which chapters did your student spend time doing lessons</p>
            </div>
            <div class="box box5">
              <h4>Points</h4>
              <canvas id="dayChart"></canvas>
            </div>
          </div>
        </div>

         
           <div class="reports_table">
              <div class="table-responsive"> 
                  <table class="table table-striped table-hover report-table">
                        <thead>
                          <tr>
                            <th>Date</th>
                            <th>Subject</th>
                            <th>Chapter</th>
                            <th>Lesson</th>
                            <th>Attempts</th>
                            <th>Points</th>
                            <th>Percentage</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php foreach ($testAttempts as $row): ?>
                            <?php
                                $lesson       = $row['lesson']   ?? null;
                                $rowSubject   = $row['subject']  ?? ($lesson ? $lesson->subject : null);
                                $rowChapter   = $row['chapter']  ?? ($lesson ? $lesson->chapter : null);
                                $attempt      = $row['latest_attempt'] ?? null;
                                $attemptCount = $row['attempts'] ?? 0;

                                $lessonId     = $lesson?->id;

                                // ✅ Prefer normalized values coming from controller
                                $subjectIdDisplay    = $row['subjectId']   ?? ($rowSubject?->id ?? null);
                                $subjectTitleDisplay = $row['subjectTitle']?? ($rowSubject?->title ?? 'N/A');

                                $chapterTitle = $rowChapter?->title ?? 'N/A';
                                $chapterId    = $rowChapter?->id ?? null;

                                $totalPoints  = $lessonId ? \frontend\models\LessonContent::find()->where(['lesson_id'=>$lessonId])->sum('points') : 0;
                                $percentage   = ($totalPoints > 0 && $attempt) ? number_format(($attempt->score / $totalPoints) * 100, 2) : 0;
                                $percentage   = min($percentage, 100);
                            ?>

                            <tr>
                              <td><?= $attempt ? Yii::$app->formatter->asDate($attempt->created_at, 'php:Y-m-d') : '-' ?></td>

                              <!-- ✅ Subject: normalized (2/5 => "English") -->
                              <td>
                                <?php if ($subjectIdDisplay): ?>
                                  <?= \yii\helpers\Html::a(
                                        \yii\helpers\Html::encode($subjectTitleDisplay),
                                        'https://beta.aplustudents.com/grade/grades?id='.$selectedStudent.'&subjectid='.$subjectIdDisplay
                                      ) ?>
                                <?php else: ?>
                                  <?= \yii\helpers\Html::encode($subjectTitleDisplay) ?>
                                <?php endif; ?>
                              </td>

                              <td>
                                <?php if ($chapterId): ?>
                                  <?= \yii\helpers\Html::a(
                                        \yii\helpers\Html::encode($chapterTitle),
                                        'https://beta.aplustudents.com/lesson?id='.$chapterId
                                      ) ?>
                                <?php else: ?>
                                  <?= \yii\helpers\Html::encode($chapterTitle) ?>
                                <?php endif; ?>
                              </td>

                              <td>
                                <?= $lesson
                                    ? \yii\helpers\Html::a(\yii\helpers\Html::encode($lesson->title),
                                        'https://beta.aplustudents.com/lesson-content/tutorial?lesson_id='.$lesson->id)
                                    : 'Lesson Not Found' ?>
                              </td>

                              <td><?= $attemptCount ?></td>
                              <td><?= $attempt ? \yii\helpers\Html::encode($attempt->points_earned) : '-' ?></td>
                              <td><?= \yii\helpers\Html::encode($percentage) ?>%</td>
                            </tr>
                          <?php endforeach; ?>
                        </tbody>
                      </table>

                      <!-- Pagination controls -->
                      <?= LinkPager::widget([
                            'pagination' => $pagination,
                            'hideOnSinglePage' => true,
                      ]) ?>

              </div>
           </div>
      </div>
    </div>
  </div>
</div>






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
}

.introjs-button:hover {
    background-color: #17a093 !important;
}

.custom-gray {
    background: #ddd !important;
    color: #000 !important;
    margin-right: 10px !important;
}
.custom-gray:hover { color:#fff !important; }

.introjs-tooltip.introjs-custom-final .introjs-prevbutton,
.introjs-tooltip.introjs-custom-final .introjs-nextbutton,
.introjs-tooltip.introjs-custom-final .introjs-donebutton {
    display: none !important;
}



</style>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.1.0/introjs.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.1.0/intro.min.js"></script>

<!-- tool tip script -->
<?php
$script = <<<JS
// (function () {
 
//   const KEY = 'tutorialDismissed_progress';
//   if (localStorage.getItem(KEY)) return;
 
//   const steps = [
//     document.querySelector('#student_id') && {
//       element: document.querySelector('#student_id'),
//       intro: 'Select a child to load relevant performance data.',
//       position: 'bottom'
//     },
//     document.querySelector('.filter_btn') && {
//       element: document.querySelector('.filter_btn'),
//       intro: 'After selecting filters, click here to load your report.',
//       position: 'bottom'
//     },
     
//     { intro: 'Setup Complete!', position: 'bottom', disableInteraction: true }
//   ].filter(Boolean);

//   if (!steps.length) return;

//   const intro = introJs();
//   intro.setOptions({
//     steps,
//     showStepNumbers: false,
//     showBullets: false,
//     overlayOpacity: 0.6,
//     exitOnOverlayClick: true,
//     showButtons: true, 
//     nextLabel: 'Next',
//     prevLabel: 'Back'
//   });

//   function patchFooterIfLast() {
//     const isLast = intro._currentStep === intro._introItems.length - 1;
//     const footer = document.querySelector('.introjs-tooltipbuttons');
//     if (!footer) return;

//     if (isLast) {
       
//       footer.innerHTML = '';

//       const dont = document.createElement('button');
//       dont.className = 'introjs-button custom-gray';
//       dont.textContent = "Don't show again";
//       dont.onclick = function () {
//         localStorage.setItem(KEY, 'true'); 
//         intro.exit();
//       };

//       const ok = document.createElement('button');
//       ok.className = 'introjs-button';
//       ok.textContent = 'OK, got it';
//       ok.onclick = function () { intro.exit(); };

//       footer.appendChild(dont);
//       footer.appendChild(ok);
//     }
//   }

//   intro.onafterchange(patchFooterIfLast);
//   intro.onchange(patchFooterIfLast);

   
//   setTimeout(function () { intro.start(); }, 400);
// })();
JS;
$this->registerJs($script);
?>





 





<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
     
    var performanceData = {
        labels: ['Over 80% and above', '51% to 79%', '50% and Below'],
        datasets: [{
            data: [
                <?= $performanceCounts['Excellent'] ?>,
                <?= $performanceCounts['Average'] ?>,
                <?= $performanceCounts['Requires Attention'] ?>
            ],
            backgroundColor: ['#1EB2A6', '#36A2EB', '#C91E1D']
        }]
    };

    var chapterLabels = <?= json_encode($chapterLabels) ?>;
    var chapterCounts = <?= json_encode($chapterCounts) ?>;
    var chapterLinks = <?= json_encode($chapterLinks) ?>;
     
    var dayData = {
        labels: <?= json_encode($dayData['labels']) ?>,
        datasets: [
            {
                label: 'Points Awarded',
                data: <?= json_encode($dayData['correct']) ?>,
                backgroundColor: '#36A2EB'
            }
        ]
    };

    // Create the charts
    window.onload = function() {
        var ctxPerformance = document.getElementById('performanceChart').getContext('2d');
        var performanceChart = new Chart(ctxPerformance, {
            type: 'pie',
            data: performanceData,
            options: {
                responsive: true,
                legend: {
                    position: 'top'
                },
                title: {
                    display: true,
                    text: 'Performance'
                },
                onClick: function(evt, activeElements) {
                    if (activeElements && activeElements.length > 0) {
                        var clickedIndex = activeElements[0]._index || activeElements[0].index;
                        var label = performanceData.labels[clickedIndex];
                        var performanceParam = "";
                        if (label === "Over 80% and above") {
                            performanceParam = "Excellent";
                        } else if (label === "51% to 79%") {
                            performanceParam = "Average";
                        } else if (label === "50% and Below") {
                            performanceParam = "Requires Attention";
                        }
                        if (performanceParam !== "") {
                            // ✅ Get current filters
            let studentId = document.getElementById("student_id").value;
            let subjectId = document.getElementById("subject_id").value;
            let date = document.getElementById("date").value;

            // ✅ Build URL with filters
            let baseUrl = "<?= Yii::$app->urlManager->createUrl(['reports/filtered-results']) ?>";
            let fullUrl = baseUrl + "?performance=" + performanceParam
                        + "&student_id=" + encodeURIComponent(studentId)
                        + "&subject_id=" + encodeURIComponent(subjectId)
                        + "&date=" + encodeURIComponent(date);

            window.location.href = fullUrl;
                        }
                    }
                }
            }
        });

        // Chapters Practiced Chart
        var ctxChapter = document.getElementById('chapterChart').getContext('2d');
        new Chart(ctxChapter, {
            type: 'doughnut',
            data: {
                labels: chapterLabels,
                datasets: [{
                    data: chapterCounts,
                    backgroundColor: ['#C91E1D', '#36A2EB', '#FFCE56', '#1EB2A6', '#FF6384', '#36A2EB', '#FFCE56']
                }]
            },
            options: {
                responsive: true,
                legend: { position: 'top' },
                title: { display: true, text: 'Practice by Chapter' },
                onClick: function(evt, activeElements) {
                    if (activeElements.length > 0) {
                        var index = activeElements[0].index;
                        var link = chapterLinks[index];
                        // if (link) window.open(link, '_blank');
                        if (link) window.location.href = link;

                    }
                }
            }
        });

        // var ctxDay = document.getElementById('dayChart').getContext('2d');
        // new Chart(ctxDay, {
        //     type: 'bar',
        //     data: dayData,
        //     options: {
        //         responsive: true,
        //         legend: {
        //             position: 'top'
        //         },
        //         title: {
        //             display: true,
        //             text: 'Points Earned per Month'
        //         },
        //         scales: {
        //             xAxes: [{
        //                 // Remove slanted labels by setting appropriate options (for Chart.js 2.x)
        //                 ticks: {
        //                     autoSkip: false,
        //                     maxRotation: 0,
        //                     minRotation: 0
        //                 }
        //             }]
        //         }
        //     }
        // });


        var ctxDay = document.getElementById('dayChart').getContext('2d');
        new Chart(ctxDay, {
            type: 'bar',
            data: dayData,
            options: {
                responsive: true,
                legend: {
                    position: 'top'
                },
                title: {
                    display: true,
                    text: 'Points Earned per Month'
                },
                scales: {
                    xAxes: [{
                        ticks: {
                            autoSkip: false,
                            maxRotation: 0,
                            minRotation: 0
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            beginAtZero: true // Ensure bars start from zero
                        }
                    }]
                }
            }
        });



    };
</script>


<!-- <script>
    flatpickr("#date", {
        mode: "range",
        dateFormat: "Y-m-d", 
        onChange: function(selectedDates, dateStr, instance) {
          
            instance.input.value = selectedDates.length === 2 ? dateStr : '';
        }
    });

    
</script> -->

<script>
  (function() {
    const form = document.querySelector('.reports_filter_form form');
    const dateInput = document.getElementById('date');

    // PHP se aayi value ko defaultDate bana do
    const defaultRange = <?= json_encode($selectedDateRange ? explode(' to ', $selectedDateRange) : []) ?>;

    flatpickr("#date", {
      mode: "range",
      dateFormat: "Y-m-d",
      defaultDate: defaultRange,
      onChange: function(selectedDates, dateStr, instance) {
        instance.input.value = (selectedDates.length === 2) ? dateStr : '';
      },
      onClose: function(selectedDates, dateStr, instance) {
        // range complete hote hi form auto-submit
        if (selectedDates.length === 2 && form) {
          form.requestSubmit ? form.requestSubmit() : form.submit();
        }
      }
    });
  })();
</script>


<script>
(function(){
  const form  = document.querySelector('.reports_filter_form form');
  const date  = document.getElementById('date');
  const radios = document.querySelectorAll('.quick-radio-group input[name="quick"]');

  radios.forEach(r=>{
    r.addEventListener('change', function(){
      // quick select par manual date ko server decide karne do (clear UI)
      if (this.value) date.value = '';
      // Custom select (blank option) pe user apni date choose karega
      form.requestSubmit ? form.requestSubmit() : form.submit();
    });
  });
})();
</script>




<script>
    document.getElementById("clearFilters").addEventListener("click", function() {
        document.getElementById("student_id").value = "";
        document.getElementById("subject_id").value = "";
        document.getElementById("date").value = "";
        document.getElementById("performance").value = "";

        // NEW: uncheck quick radios (select Custom/blank)
    const qRadios = document.querySelectorAll('.quick-radio-group input[name="quick"]');
    qRadios.forEach(r => r.checked = (r.value === ''));

        window.location.href = "<?= Yii::$app->urlManager->createUrl(['reports/progress-report']) ?>"; 
    });
</script>



