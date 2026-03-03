<?php
/**
 * @author Jawwad Ahmed <jawwad.software@gmail.com>
 * @var yii\web\View $this
 */

use rmrevin\yii\fontawesome\FAS;
use miloschuman\highcharts\Highcharts;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\bootstrap4\Html;
use yii\helpers\ArrayHelper;

$this->title = Yii::t('backend', 'Applications Reports')." (".$intake->title." ".$intake->year. ")";
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Dashboard'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$icons = [
    'user' => FAS::icon('user', ['bg-blue'])
];
$applicationModel = new frontend\modules\studentms\models\ApplicationDetail();?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
              <div class="card-header border-transparent">
                <h3 class="card-title">Recent Applications</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0">
                <div class="table-responsive">
                  <?php
                        $searchModel = new backend\modules\studentms\models\search\ApplicationDetailSearch();
                        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                        $dataProvider->pagination->pageSize = 5;
//                        $dataProvider->pagination = false;
                    ?>
                  <?php echo GridView::widget([
                'layout' => "{items}\n",
                'options' => [
                    'class' => ['gridview', 'table-responsive'],
                ],
                'tableOptions' => [
                    'class' => ['table', 'text-nowrap', 'table-striped', 'table-bordered', 'mb-0'],
                ],
                'dataProvider' => $dataProvider,
//                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

//                    [ 'attribute' =>'application_id', 'label'=>'ID'],  
                   
                    'title',
                    ['attribute' =>'forename', 'format'=>'html', 'value'=> function ($model){return Html::a($model->forename, '/studentms/application-detail/view?id='.$model->application_id);}],
//                    'surname',
                     'gender',
                    
                    ['attribute' => 'proposed_programme',
                    'value'=> function($model){return substr($model->programme->title,0,25);},
                    'filter' =>   Html::dropdownList(
                    'ApplicationDetailSearch[proposed_programme]',$_GET['ApplicationDetailSearch']['proposed_programme'],
                        ArrayHelper::map( //Yii::$app->user->isSuperadmin?
                            \backend\modules\studentms\models\Course::find()
                                ->select(['id', 'title'])
                                ->where('status != 0' )->orderBY(['title'=>SORT_ASC])->all(),
//                                :
//                            Yii::$app->user->school->studentClasses, 
                                'id','title')
                               , ['class'=>'form-control', 'prompt'=>'Select Programme']
                        ),
                         'headerOptions' => ['style' => 'width:10%'],    
                    ],
//                    'date_of_birth',
//                    [ 'attribute' =>'nationality', 'label'=>'Nationality'],
                    [ 'attribute' =>'ethnicity', 'label'=>'Ethnicity', 'value' =>function($model){
                        return substr($model->ethnicityList[$model->ethnicity],0, 10);}],
                    [ 'attribute' =>'require_visa', 'label'=>'Req: Visa'],

//                    [ 'attribute' =>'campus_location', 'label'=>'Campus', 'value' =>function($model){
//                        return substr($model->campusLocationList[$model->campus_location], 0, 9);}],    

                    [
                        'attribute' => 'status',
                        'format'=>'applicationlabel',    
//                        'value' => function($model) { return $model->status == '1' ? 
//                                        "<span class='label label-success'>Active</span>" : "<span class='label label-danger'>In Active</span>";},
                        'filter' =>   Html::dropdownList(
                        'ApplicationDetailSearch[status]',$_GET['ApplicationDetailSearch']['status'],
                        [ '0' => 'Pending', '5' => 'In Progress', '1' => 'Approved',
                    '-10'=> 'Rejected', '10' => 'Enrolled' ],        
                        ['class'=>'form-control', 'prompt'=>'Select Status']
                        ),
                         'contentOptions' => ['style' => 'width:50px; text-align:center']                       
                    ],        
                     'created_at:date',
                ],
            ]); ?>
                </div>
                <!-- /.table-responsive -->
              </div>
              <!-- /.card-body -->
              <div class="card-footer clearfix">
                <!--<a href="javascript:void(0)" class="btn btn-sm btn-info float-left">Place New Order</a>-->
                <a href="/studentms/application-detail" class="btn btn-sm btn-secondary float-right">View All Applications</a>
              </div>
              <!-- /.card-footer -->
            </div>
        
    </div>
</div>
<?php foreach ($reports as $primekey => $report) {

    $data = [];
    foreach ($report as $key => $record) {

        $data[$key]['name'] = backend\modules\studentms\models\Course::findOne($record->proposed_programme)->title;
        if($primekey == 'ethnicity')
            $data[$key]['name'] = $applicationModel->ethnicityList[$record->ethnicity];
        if($primekey == 'campus_location')
            $data[$key]['name'] = $applicationModel->campusLocationList[$record->campus_location];
        if($primekey == 'mode_of_study')
            $data[$key]['name'] = $applicationModel->modeOfStudyList[$record->mode_of_study];
        if($primekey == 'nationality')
            $data[$key]['name'] = $record->nationality;
		if($primekey == 'gender')
            $data[$key]['name'] = $record->gender;
        $data[$key]['y'] =  (int)$record->application_count;

    }
    
    $dataProvider = new ArrayDataProvider([
    'allModels' => $data,
    'sort' => [
        'attributes' => [ 'id', 'username', 'email'],
    ],
    'pagination' => [
        'pageSize' => 42,
    ],
]);
?>

<?php \yii\widgets\Pjax::begin() ?>


<div class="row">
    <div class="col-md-12">
        <div class="card">
              <div class="card-header border-transparent">
                  <h3 class="card-title">Applications By <?php echo ucfirst($primekey)?></h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0">
                  <div class="row">
                             
        
                  
                <!-- /.table-responsive -->
                <div class="col-md-6">
                <?php echo Highcharts::widget([
                        'scripts' => [
                            'modules/exporting',
                    //        'themes/grid-light',
                        ],
                        'options' => [
                            'title' => [
                                'text' => ' By '.ucfirst($primekey),
                                'height' => '800px'
                            ],

                            'series' => [
                                [
                                    'type' => 'pie',
                                    "dataLabels"=> [
                                                    "enabled"=> true,
                                                    "format"=> "<b>{point.name}</b>: {point.percentage:.1f} %",
                                                    "style"=> [
                                                            "color"=> "black"
                                                    ]
                                            ],
                                    'name' => 'Applications',
                                    'data' => $data , //[["name"=>'A', "y"=>20], ["name"=>'2A', "y"=>50], ["name"=>'3A', "y"=>30], ["name"=>'7A', "y"=>70], ],
                    //                'center' => [170, 50],
                                    'size' => 150,
                                    'showInLegend' => true,
                    //                'dataLabels' => [
                    //                    'enabled' => false,
                    //                ], //default
                                    "plotOptions"=> [
                                    "pie"=> [
                                            "allowPointSelect"=> true,
                                            "cursor"=> "pointer",
                                            "dataLabels"=> [
                                                    "enabled"=> true,
                                                    "format"=> "<b>{point.name}</b>: {point.percentage:.1f}%",
                                                    "style"=> [
                                                            "color"=> "black"
                                                    ]
                                            ]
                                    ]
                                    ]

                                ],
                            ],
                        ]
                    ]);


                        ?> 
              </div>
                <div class="col-md-5">
                      <?php echo GridView::widget([
                    'dataProvider' => $dataProvider,
                     'layout' => "{items}\n",      
                    'id'=>'investigation-'.$primekey.'-grid',        
            //                'filterModel' => $searchModel,

                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn' , 'headerOptions' => ['style' => 'width:5%']],
//                        'id',
                        [ 'label'=>ucfirst($primekey),'value' =>'name', 'headerOptions' => ['style' => 'width:50%']   ],
                        [ 'attribute' => 'y' , 'label'=>'Applications', 'headerOptions' => ['style' => 'width:10%']],

                        ]
        ]); ?> 
                
                  
                
                </div>
              </div>
              </div>
              <!-- /.card-body -->
              <div class="card-footer clearfix">
                <!--<a href="javascript:void(0)" class="btn btn-sm btn-info float-left">Place New Order</a>-->
                <a href="javascript:void(0)" class="btn btn-sm btn-secondary float-right">View All Applications</a>
              </div>
              <!-- /.card-footer -->
            </div>
        
    </div>
    
</div>
<?php } ?>