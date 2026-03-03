<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

use yii\widgets\Pjax;


/**
 * @var yii\web\View $this
 * @var backend\models\search\StudentSearch $searchModel
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = 'Students';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="student-index">
    <div class="card">
        <div class="card-header">
            <?php echo Html::a('Create Student', ['create'], ['class' => 'btn btn-success']) ?>
        </div>

        <div class="card-body p-0">
            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

             <div id="grid-wrap" class="grid-wrap">
    
            <?php echo GridView::widget([
                'layout' => "{items}\n{pager}",
               'options' => [
                    'class' => 'gridview table-responsive',
                    'style' => 'overflow-x:auto;'
                ],
                'tableOptions' => [
                    'class' => 'table text-nowrap table-striped table-bordered mb-0',
                    'style' => 'min-width:1200px;'
                ],


                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    // 'id',
                    'full_name',
                    // [
                    //     'attribute' => 'parent_id',
                    //    'label' => 'Parent',
                    //     'value' => function($model) {
                    //         return $model->parent ? $model->parent->username : 'No Parent';
                    //     },
                    //     'filter' => ArrayHelper::map(\backend\models\User::find()->all(), 'id', 'username'),
                    // ],
                    // 'email',
                    // 'details:ntext',
                    // 'grade_id',
                      // Parent Last Name
                    [
                        'attribute' => 'parent_last_name',
                        'label'     => 'Last Name',
                        'value'     => fn($m) => $m->parent->lname ?? '—',    // <-- lname
                        'filterInputOptions' => [
                            'class' => 'form-control',
                            'placeholder' => 'Filter by last name'
                        ],
                    ],

                   
                 
                    // [
                    //     'attribute' => 'parent_email',
                    //     'label'     => 'Parent Email',
                    //     'value'     => fn($m) => $m->parent->email ?? '—',
                    //     'filterInputOptions' => ['class'=>'form-control','placeholder'=>'Filter by parent email'],
                    // ],

                 

                    // Parent Phone
                    [
                        'attribute' => 'parent_phone',
                        'label'     => 'Phone',
                        'value'     => fn($m) => $m->parent->phone ?? '—',    // <-- phone
                        'filterInputOptions' => [
                            'class' => 'form-control',
                            'placeholder' => 'Filter by phone'
                        ],
                    ],

                    // (Updated) Parent Email (as-is)
                    [
                        'attribute' => 'parent_email',
                        'label'     => 'Parent Email',
                        'value'     => fn($m) => $m->parent->email ?? '—',
                        'filterInputOptions' => [
                            'class'=>'form-control',
                            'placeholder'=>'Filter by parent email'
                        ],
                    ],



                    // [
                    //     'attribute' => 'parent_email',      // filter StudentSearch::$parent_email se bind
                    //     'label'     => 'Parent Email',
                    //     'format'    => 'raw',               // taake link render ho
                    //     'value'     => function($model){
                    //         if (!$model->parent) return 'No Parent';
                    //         // email pe click => isi page par parent_id filter lag jaye
                    //         return Html::a(
                    //             Html::encode($model->parent->email),
                    //             ['index', 'StudentSearch[parent_id]' => $model->parent_id],
                    //             ['title' => 'Show all students of this parent']
                    //         );
                    //     },
                    //     'filterInputOptions' => [
                    //         'class' => 'form-control',
                    //         'placeholder' => 'Search parent email',
                    //     ],
                    // ],
                    
                     
                     [
                        'attribute' => 'grade_id',
                        'label' => 'Grade',
                        'value' => function($model) {
                            return $model->grade ? $model->grade->title : 'No Grade';
                        },
                        'filter' => ArrayHelper::map(\frontend\models\Grade::find()->all(), 'id', 'title'),
                    ],

                    [
                        'attribute' => 'gender',
                        'filter' => [
                            1 => 'Male',
                            2 => 'Female',
                            0 => 'Other',
                        ],
                        'value' => function($model) {
                            $genders = [
                                1 => 'Male',
                                2 => 'Female',
                                0 => 'Other',
                            ];
                            return isset($genders[$model->gender]) ? $genders[$model->gender] : 'Unknown';
                        },
                    ],

                    
                    'dob',
                    // 'live_support',
                    
                     

                    [
                        'attribute' => 'has_parent_email',
                        'label'     => 'Has Parent Email?',
                        'value'     => fn($m) => ($m->parent && !empty($m->parent->email)) ? 'Yes' : 'No',
                        'filter'    => [1 => 'Yes', 0 => 'No'],
                    ],   
                    [
                        'attribute' => 'status',
                        'filter' => [
                            1 => 'Active',
                            0 => 'Inactive',
                        ],
                        'value' => function($model) {
                            return $model->status == 1 ? 'Active' : 'Inactive';
                        },
                    ],
                //     [
                //     'attribute' => 'total_points',
                //     'label'     => 'Total Points',
                //     'value'     => function($model){
                         
                //         if (isset($model->total_points)) {
                //             return (int)$model->total_points;
                //         }
                      
                //         return $model->getTotalPointsAccurate();
                //     },
                //     'format'    => 'integer',
                // ],

                [
                    'attribute' => 'total_points',   // CHANGE: StudentSearch se bind
                    'label'     => 'Total Points',
                    'value'     => function($model){
                        if (isset($model->total_points)) {
                            return (int)$model->total_points;
                        }
                        return $model->getTotalPointsAccurate();
                    },
                    'format'    => 'integer',
                    // CHANGE: filter box dikhane ke liye
                    'filterInputOptions' => [
                        'class' => 'form-control',
                        'type'  => 'number',
                        'placeholder' => 'Filter by points',
                    ],
                ],




                    // [
                    //     'class' => 'yii\grid\ActionColumn',
                    //     'template' => '{view} {update} {delete} {edit-points}',
                    //     'buttons' => [
                    //         'edit-points' => function ($url, $model) {
                    //             return Html::a('Edit Points', ['edit-points', 'id' => $model->id], [
                    //                 'class' => 'btn btn-primary btn-sm',
                    //             ]);
                    //         },
                    //     ],
                    // ],

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view} {update} {delete} {add-score}', // Add the add-score button to the grid
                        'buttons' => [
                            'add-score' => function ($url, $model) {
                                // Return the link with the student_id parameter
                                return Html::a('Add/Edit Score', ['student/add-score', 'student_id' => $model->id], [
                                    'class' => 'btn btn-primary btn-sm',
                                ]);
                            },
                        ],
                    ],
                    
                    
                    
                    
                    // 'created_by',
                    // 'updated_by',
                    // 'updated_at',
                    // 'created_at',
                    
                    ['class' => \common\widgets\ActionColumn::class],
                ],
            ]); ?>

            </div> <!-- /grid-wrap -->

            <!-- hamesha bottom pe visible horizontal scrollbar -->
            <div id="grid-xbar" class="grid-xbar"><div class="grid-xbar-inner"></div></div>
    
        </div>
        <div class="card-footer">
            <?php echo getDataProviderSummary($dataProvider) ?>
        </div>
    </div>

</div>



<?php
$this->registerCss(<<<CSS
.grid-wrap{
  position: relative;
  overflow: visible;
}

.student-index{ padding-bottom: 40px; }

/* bottom overlay scrollbar */
.grid-xbar{
  position: fixed;
  left: 0;
  width: 100%;
  bottom: 8px;
  height: 10px;
  overflow-x: auto;
  overflow-y: hidden;
  background: rgb(30 29 29 / 50%);
  border-radius: 6px;
  z-index: 2000;
  opacity: .70;
  transition: opacity .15s ease;
}
.grid-xbar:hover{ opacity: .9; }

.grid-xbar-inner{ height: 1px; }

.grid-xbar::-webkit-scrollbar{ height: 8px; }
.grid-xbar::-webkit-scrollbar-thumb{
  background: rgba(0,0,0,.35);
  border-radius: 6px;
}
CSS);

?>



<?php
$this->registerJs(<<<JS
function bindGridXbar(){
  var container = document.getElementById('grid-wrap');
  var scroller  = container ? container.querySelector('.gridview.table-responsive') : null;
  var xbar      = document.getElementById('grid-xbar');
  var inner     = xbar ? xbar.querySelector('.grid-xbar-inner') : null;

  if(!container || !scroller || !xbar || !inner){ return; }

  function syncWidths(){
    inner.style.width = scroller.scrollWidth + 'px';
  }
  function syncToXbar(){ xbar.scrollLeft = scroller.scrollLeft; }
  function syncToGrid(){ scroller.scrollLeft = xbar.scrollLeft; }

  function positionBar(){
    var r = scroller.getBoundingClientRect();
    var onScreen = r.bottom > 0 && r.top < window.innerHeight;
    xbar.style.display = onScreen ? 'block' : 'none';
    if(!onScreen) return;

    var left  = Math.max(0, r.left);
    var right = Math.min(window.innerWidth, r.right);
    var width = Math.max(0, right - left);

    xbar.style.left  = left + 'px';
    xbar.style.width = width + 'px';
  }

  // remove old listeners if any
  if(scroller._sync){ scroller.removeEventListener('scroll', scroller._sync); }
  if(xbar._sync){     xbar.removeEventListener('scroll', xbar._sync); }
  if(window._gridXbarResize){ window.removeEventListener('resize', window._gridXbarResize); }
  if(window._gridXbarScroll){ window.removeEventListener('scroll', window._gridXbarScroll); }

  scroller._sync = syncToXbar;
  xbar._sync     = syncToGrid;
  scroller.addEventListener('scroll', scroller._sync, {passive:true});
  xbar.addEventListener('scroll',     xbar._sync,     {passive:true});

  window._gridXbarResize = function(){ syncWidths(); positionBar(); };
  window._gridXbarScroll = function(){ positionBar(); };
  window.addEventListener('resize', window._gridXbarResize, {passive:true});
  window.addEventListener('scroll', window._gridXbarScroll, {passive:true});

  syncWidths();
  syncToXbar();
  positionBar();
}

// first render
bindGridXbar();
JS);

?>