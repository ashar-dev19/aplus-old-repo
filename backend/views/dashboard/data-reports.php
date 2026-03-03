<?php
/**
 * @author Jawwad Ahmed <jawwad.software@gmail.com>
 * @var yii\web\View $this
 */

use rmrevin\yii\fontawesome\FAS;
use miloschuman\highcharts\Highcharts;
use yii\data\ActiveDataProvider;

use yii\grid\GridView;
use yii\bootstrap4\Html;
use yii\helpers\ArrayHelper;



$this->title = Yii::t('backend', 'MIHE Data Reports');
$icons = [
    'user' => FAS::icon('user', ['bg-blue'])
];

$formatter = Yii::$app->formatter;
?>

<?php \yii\widgets\Pjax::begin() ?>
<?php 

$liveStudentCount = backend\modules\studentms\models\Student::find()->where(['status'=>1])->count();
$graduatedStudentCount = backend\modules\studentms\models\Student::find()->where(['status'=>10])->count();
$deferredStudentCount = backend\modules\studentms\models\Student::find()->where(['status'=>4])->count();

$intake = \backend\modules\studentms\models\Intake::find()->admission()->one();
$applicationCount = frontend\modules\studentms\models\ApplicationDetail::find()->where(['intake_id'=>$intake->id])->count();
//print_r($dataReport);
?>
<div class="row">
    <div col-lg-12>
    <div class="callout callout-info m-4">
                  <h5><?php echo Yii::$app->keyStorage->get('backend.help.text');?></h5>

                  <p><?php echo Yii::$app->keyStorage->get('backend.help.data-reports');?>
            </div>
    </div>
</div>
<div class="row">
    
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-primary">
              <div class="inner">
                  <h3><?php echo $liveStudentCount;?></h3>

                <p>Live Students</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="/studentms/student?StudentSearch[status]=1" class="small-box-footer">View <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                  <h3>Students</h3>

                <p >By course & intake</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <!--<a href="/studentms/application-detail" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
              <a href="/studentms/enrollment" class="small-box-footer">View <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-red">
              <div class="inner">
                  <h3>Students</h3>

                <p >By Modules & results</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <!--<a href="/studentms/application-detail" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
              <a href="/assessmentms/assessment-result/report" class="small-box-footer">View <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
</div>

<div class="row">
          <div class="col-4">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Overall Student Statistics</h3>

                
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                  
                  </tbody>
                  <tr>
                      <td><strong>Live</strong></td>
                      <td><?php echo $liveStudentCount ?></td>
                  </tr>
                  <tr>
                      <td><strong>Suspended /Deferred</strong></td>
                      <td> <?php print $deferredStudentCount?> </td>
                  </tr>
                  <tr>
                      <td><strong>Graduated</strong></td>
                      <td><?php echo $graduatedStudentCount;?></td>
                  </tr>
                  <tr>
                      <td><strong>New Applications</strong></td>
                      <td><?php echo $applicationCount;?></td>
                  </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
            <div class="col-md-6">
                <div class="card">
              <div class="card-header">
                <h3 class="card-title">Visa Expiry</h3>

                <div class="card-tools">Next 8 weeks
                  <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
                  </button>
                    
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0">
                <div class="table-responsive">
                    <?php
                        $query = backend\modules\studentms\models\Student::find()->where(['status' => 1])
                                ->andWhere(['<', 'visa_expiry', date('Y-m-d', strtotime('+ 8 weeks')) ])
                                ->andWhere('visa_expiry is not  NULL')
                                ->orderBy(['visa_expiry'=>SORT_ASC]);

                        $provider = new ActiveDataProvider([
                            'query' => $query,
                            'pagination' => [
                                'pageSize' => 10,
                            ],
                            'sort' => [
                                'defaultOrder' => [
                                    'created_at' => SORT_DESC,
                                    'first_name' => SORT_ASC, 
                                ]
                            ],
                        ]);
                    ?>
                  <?php echo GridView::widget([
                'layout' => "{items}\n",
                'options' => [
                    'class' => ['gridview', 'table-responsive'],
                ],
                'tableOptions' => [
                    'class' => ['table', 'text-nowrap', 'table-striped', 'table-bordered', 'mb-0'],
                ],
                'dataProvider' => $provider,
//                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    ['attribute' =>'first_name',  'label'=>'Student', 'format'=>'html', 'value'=> function ($model){return Html::a($model->first_name, '/studentms/student/view?id='.$model->id);}],
                    [ 'attribute' =>'visa_expiry', 'label'=>'Expire On', 'format'=>'date'], 
                    ['label'=>"in Days", 'format'=>'html', 'contentOptions'=>[],
                    'value'=> function($model) use ($formatter){
                        $date1=date_create($model->visa_expiry);
                        $date2 = date_create(date('Y-m-d'));$diff =date_diff($date1,$date2);
                        return ($diff->format("%a") > 15)?  "<h6><span class='badge badge-warning'>".$diff->format("%a days")."</span></h6>":
                             "<h5><span class='badge badge-danger'>".$diff->format("%a days")."</span><h5>"
                            ;}],

                ],
            ]); ?>
                </div>
                
              </div>
              <!-- /.card-body -->
              
              <!-- /.footer -->
            </div>
            </div>
        </div>
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Live(current) Students Report </h3>
                
                <div class="col-md-12 text-right">
                <?php echo Html::a('<i class="fa fa-print"></i> Print', null,
                            ['class' => 'btn btn-primary', 'data-toggle' => "collapse", 'id' => "grid-print", 
                                'data'=> GuzzleHttp\json_encode(array('grid_name'=>'student-table', 'hide_col' =>'1001',
                                    'sub_title'=>'Enrolled Students List', 'tblPadding'=>'10px', 'logo_image' => 'mihe_logo', 'main_title' => '')) ]) ?>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0" id="student-table">
                <table class="table table-hover table-bordered text-nowrap">
                  
                  <tbody>
                      <tr>
                          <td><strong>Course</strong></td>
                          <td><strong>Live</strong></td>
                          <td><strong>Male</strong></td>
                          <td><strong>Female</strong></td>
                          <td><strong>UK /EU </strong></td>
                          
                          <td><strong>Overseas </strong></td>
                          <td><strong>White </strong></td>
                          <td><strong>Other Ethnic </strong></td>
                          <td><strong>Disable</strong></td>
                          <td><strong>Over 21 </strong></td>
                          <td><strong>Under 21 </strong></td>
                      </tr>
                  <?php foreach ($dataReport as $key => $course) :?>
                      <tr>
                      
                      
                          <?php print '<td>'. $course['title'] .'</td>';?>
                          <?php print '<td>'. $course['student_count'] .'</td>';?>
                          <?php print '<td>'. $course['male_count'] .'</td>';?>
                          <?php print '<td>'. $course['female_count'] .'</td>';?>
                          <?php print '<td>'. $course['ukstudent_count'] .'</td>';?>
                          <?php print '<td>'. $course['overseastudent_count'] .'</td>';?>
                          <?php print '<td>'. $course['whitestudent_count'] .'</td>';?>
                          <?php print '<td>'. $course['otherstudent_count'] .'</td>';?>
                          <?php print '<td>'. $course['disable_count'] .'</td>';?>
                          <?php print '<td>'. $course['twplus_count'] .'</td>';?>
                          <?php print '<td>'. $course['tw_count'] .'</td>';?>
                      
                      
                  </tr>
                  <?php endforeach;?>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>


<div class="row">
          
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Application Report for Current Intake:  <?php echo $intake->title; ?> </h3>
                <div class="col-md-12 text-right">
                <?php echo Html::a('<i class="fa fa-print"></i> Print', null,
                            ['class' => 'btn btn-primary grid-print', 'data-toggle' => "collapse", 'id' => "grid-print-app", 
                                'data'=> GuzzleHttp\json_encode(array('grid_name'=>'application-table', 'hide_col' =>'1001',
                                    'sub_title'=>'Application List', 'tblPadding'=>'10px', 'logo_image' => 'mihe_logo', 'main_title' => '')) ]) ?>
                </div>
                
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0" id="application-table">
                <table class="table table-hover table-bordered text-nowrap">
                  
                  <tbody>
                      <tr>
                          <td><strong>Course</strong></td>
                          <td><strong>Received</strong></td>
                          <td><strong>Pending</strong></td>
                          <td><strong>Approved/ Offered</strong></td>
                          <td><strong>In Progress</strong></td>
                          <td><strong>Enrolled</strong></td>
                          <td><strong>Deffered </strong></td>
						  <td><strong>Withdrawn </strong></td>

                      </tr>
                  <?php foreach ($applicationDataReport as $key => $course) :?>
                      <tr>
                      
                      
                          <?php print '<td>'. $course['title'] .'</td>';?>
                          <?php print '<td>'. $course['application_count'] .'</td>';?>
                          <?php print '<td>'. $course['pending_count'] .'</td>';?>
                          <?php print '<td>'. $course['approved_count'] .'</td>';?>
                          <?php print '<td>'. $course['inprogress_count'] .'</td>';?>
                          <?php print '<td>'. $course['enrolled_count'] .'</td>';?>
                          <?php print '<td>'. $course['deffered_count'] .'</td>';?>
						  <?php print '<td>'. $course['withdrawn_count'] .'</td>';?>
                          
                      
                      
                  </tr>
                  <?php endforeach;?>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
<?php \yii\widgets\Pjax::end() ?>