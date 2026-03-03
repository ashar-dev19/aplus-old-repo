<?php

use webvimark\modules\UserManagement\components\GhostHtml;
use webvimark\modules\UserManagement\models\rbacDB\Role;
use webvimark\modules\UserManagement\models\User;
use webvimark\modules\UserManagement\UserManagementModule;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\Pjax;
use webvimark\extensions\GridBulkActions\GridBulkActions;
use webvimark\extensions\GridPageSize\GridPageSize;
use yii\grid\GridView;

/* @var $this        yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel webvimark\modules\UserManagement\models\search\UserSearch */

$this->title = UserManagementModule::t('back','Users');
$this->params['breadcrumbs'][] = $this->title;



 
$qs = Yii::$app->request->get();

// Inactive URL (absolute route to module)
$inactiveUrl = Url::to([
    '/user-management/user/index',
    'UserSearch' => array_merge($qs['UserSearch'] ?? [], [
        'status' => User::STATUS_INACTIVE,
    ]),
]);

// All URL (remove status)
$allSearch   = $qs['UserSearch'] ?? [];
unset($allSearch['status']);
$allUrl = Url::to([
    '/user-management/user/index',
    'UserSearch' => $allSearch,
]);

$showingInactive = isset(($qs['UserSearch'] ?? [])['status'])
    && (string)($qs['UserSearch']['status']) === (string)User::STATUS_INACTIVE;

?>



<div class="user-index">

    <h2 class="lte-hide-title"><?= $this->title ?></h2>

    <div class="panel panel-default">
        <div class="panel-body">

            <div class="row">
                <div class="col-sm-6">
                    <p>
                        <?= GhostHtml::a(
                            '<span class="glyphicon glyphicon-plus-sign"></span> ' . UserManagementModule::t('back','Create User'),
                            ['/user-management/user/create'],
                            ['class'=>'btn btn-success']
                        ) ?>
                        <?= Html::a(
                            '<span class="glyphicon glyphicon-plus-sign"></span> Add Student',
                            ['user/add-student'],
                            ['class'=>'btn btn-primary']
                        ) 
                        ?>

                         <!-- New: Inactive/All toggle -->
                        <?= Html::a(
                            $showingInactive ? 'Show All Users' : 'Show Inactive Users',
                            $showingInactive ? $allUrl : $inactiveUrl,
                            [
                                'class' => $showingInactive ? 'btn btn-default' : 'btn btn-warning',
                                'data-pjax' => 1, 
                                'style' => 'margin-left:6px'
                            ]
                        )
                        ?>

                        <?php //echo Html::a(
                            // 'Reactivate 3-year expired users',
                            // ['reactivate-expired'],
                            // ['class' => 'btn btn-info', 'data-confirm' => 'Sure to reactivate all 3-year expired users?']
                        // ) 
                        ?>

                    </p>
                </div>
                <div class="col-sm-6 text-right">
                    <?= GridPageSize::widget(['pjaxId'=>'user-grid-pjax']) ?>
                </div>
            </div>

            <?php Pjax::begin(['id'=>'user-grid-pjax']) ?>

            <!-- <div style="overflow-x:auto;"> -->
              <div id="grid-wrap" class="grid-wrap">

            
            <?= GridView::widget([
                'id'           => 'user-grid',
                'dataProvider' => $dataProvider,
                'filterModel'  => $searchModel,
                'layout'       => '{items}
                                   <div class="row">
                                     <div class="col-sm-8">{pager}</div>
                                     <div class="col-sm-4 text-right">{summary}'
                                       . GridBulkActions::widget([
                                           'gridId'=>'user-grid',
                                           'actions'=>[
                                             Url::to(['bulk-activate','attribute'=>'status'])  => GridBulkActions::t('app','Activate'),
                                             Url::to(['bulk-deactivate','attribute'=>'status'])=> GridBulkActions::t('app','Deactivate'),
                                             '----'=>[
                                               Url::to(['bulk-delete'])=>GridBulkActions::t('app','Delete'),
                                             ],
                                           ],
                                       ]) .
                                    '</div>
                                   </div>',

                'options'      => [
                    'class' => 'gridview table-responsive', 
                    'style' => 'overflow-x:auto;'
                ],
                'tableOptions' => [
                    // this forces the table to not wrap, so the scrollbar appears
                    'class' => 'table text-nowrap table-striped table-bordered mb-0',
                    'style' => 'min-width:1200px;'
                ],      

                'columns'=>[
                    // ['class'=>'yii\grid\SerialColumn','options'=>['style'=>'width:10px']],
                    ['class'=>'yii\grid\SerialColumn'],
                      [
                        'attribute' => 'fname',
                        'label'     => 'First Name',
                        'value'     => fn(User $m) => $m->fname,
                        'filter'    => Html::activeTextInput($searchModel, 'fname', ['class'=>'form-control','placeholder'=>'Search…']),
                      ],
                      [
                        'attribute' => 'lname',
                        'label'     => 'Last Name',
                        'value'     => fn(User $m) => $m->lname,
                        'filter'    => Html::activeTextInput($searchModel, 'lname', ['class'=>'form-control','placeholder'=>'Search…']),
                      ],
                      
                       [
                        'attribute' => 'phone',
                        'filter' => Html::activeTextInput($searchModel, 'phone', ['class' => 'form-control', 'placeholder' => 'Search by phone']),
                        'value' => 'phone',
                      ],
                      [
                      'attribute'=>'email',
                      'format'=>'raw',
                      'visible'=>User::hasPermission('viewUserEmail'),
                    ],

                      [
                      'attribute'=>'username',
                      'value'=>function(User $m){ return Html::a($m->username,['view','id'=>$m->id],['data-pjax'=>0]); },
                      'format'=>'raw',
                      ],
                    //  [
                    //   'attribute' => 'sales_person',
                    //   'filter' => Html::activeTextInput($searchModel, 'sales_person', ['class' => 'form-control', 'placeholder' => 'Search by sales person ID']),
                    //   'value' => function ($model) {
                    //     return $model->salesPerson ? $model->salesPerson->username : '';
                    //   },
                    // ],
                    [
                        'attribute' => 'sales_person',
                        'filter' => Html::activeTextInput($searchModel, 'sales_person', [
                            'class' => 'form-control',
                            'placeholder' => '',
                        ]),
                        'value' => function ($model) {
                            if (!$model->salesPerson) {
                                return '';
                            }
                            $sp = $model->salesPerson;
                            // FirstName LastName show karo; agar dono blank hon to username fallback
                            $full = trim(($sp->fname ?? '') . ' ' . ($sp->lname ?? ''));
                            return $full !== '' ? $full : ($sp->username ?? '');
                        },
                        'format' => 'text',
                      ],

                      
                      // [
                      //       'attribute' => 'sales_person_name',
                      //       'label'     => 'Sales Person',
                      //       'filter'    => Html::activeTextInput($searchModel, 'sales_person_name', [
                      //           'class' => 'form-control',
                      //           'placeholder' => 'Search by name…',
                      //       ]),
                      //       'value' => function ($model) {
                      //           if (!$model->salesPerson) return '';
                      //           $sp = $model->salesPerson;
                      //           $full = trim(($sp->fname ?? '') . ' ' . ($sp->lname ?? ''));
                      //           return $full !== '' ? $full : ($sp->username ?? '');
                      //       },
                      //   ],


                    [
                      'attribute' => 'address',
                      'value' => 'address',
                      'filter' => Html::activeTextInput($searchModel, 'address', [
                        'class' => 'form-control',
                        'placeholder' => 'Search by address…',
                      ]),
                    ],
                      [
                          'attribute'=>'unit_number',
                          'label'=>'Unit/Apt #',
                          'value'=>fn(User $m)=> $m->profile?->unit_number,
                          'filter'=>Html::activeTextInput($searchModel,'unit_number',['class'=>'form-control','placeholder'=>'Search…']),
                      ],
                      [
                          'attribute'=>'city',
                          'value'=>fn(User $m)=> $m->profile?->city,
                          'filter'=>Html::activeTextInput($searchModel,'city',['class'=>'form-control','placeholder'=>'Search…']),
                      ],
                      [
                          'attribute'=>'province',
                          'value'=>fn(User $m)=> $m->profile?->province,
                          'filter'=>Html::activeTextInput($searchModel,'province',['class'=>'form-control','placeholder'=>'Search…']),
                      ],
                      [
                          'attribute'=>'postal_code',
                          'label'=>'Postal Code',
                          'value'=>fn(User $m)=> $m->profile?->postal_code,
                          'filter'=>Html::activeTextInput($searchModel,'postal_code',['class'=>'form-control','placeholder'=>'Search…']),
                      ],
                      [
                          'attribute' => 'country',
                          'label'     => 'Country',
                          'value'     => fn(User $m) => $m->profile?->country,
                          'filter'    => Html::activeTextInput($searchModel, 'country', [
                              'class'       => 'form-control',
                              'placeholder' => 'Search…',
                          ]),
                      ],

					  
                  
                     
                  
                    // [
                    //       'attribute'=>'parent_firstname',
                    //       'label'=>'Parent First Name',
                    //       'value'=>fn(User $m)=> $m->profile?->parent_firstname,
                    //       'filter'=>Html::activeTextInput($searchModel,'parent_firstname',['class'=>'form-control','placeholder'=>'Search…']),
                    //   ],
                    //    [
                    //       'attribute'=>'parent_lastname',
                    //       'label'=>'Parent Last Name',
                    //       'value'=>fn(User $m)=> $m->profile?->parent_lastname,
                    //       'filter'=>Html::activeTextInput($searchModel,'parent_lastname',['class'=>'form-control','placeholder'=>'Search…']),
                    //   ],
 
                // [
                //         'attribute'=>'family_name',
                //         'label'    =>'Family Name',
                //         'value'    => fn($m)=> $m->profile?->family_name,
                //         'filter'   => Html::activeTextInput($searchModel,'family_name',[
                //             'class'=>'form-control','placeholder'=>'Search…'
                //         ]),
                // ],

                

            [
                'attribute' => 'created_at',      // sorting yahin se hoga
                'label'     => 'User Since',
                'format'    => 'raw',
                'value'     => function(\webvimark\modules\UserManagement\models\User $m){
                    return $m->created_at
                        ? Yii::$app->formatter->asDate($m->created_at, 'php:M d, Y')
                        : '—';
                },
                // TEXT FILTER: user_since
                'filter' => Html::activeTextInput(
                    $searchModel,
                    'user_since',
                    ['class'=>'form-control', 'placeholder'=>'e.g. 1970 or Aug 21']
                ),
                'contentOptions' => ['style'=>'white-space:nowrap;'],
            ],




                [
                    'attribute' => 'contract_type',
                    'label'     => 'Contract Type',
                    'filter'    => [
                        ''            => 'All',
                        '1y'          => '1 year',
                        '2y'          => '2 years',
                        '3y'          => '3 years',
                        'm2m'         => 'Month-to-month',
                        '__NOTSET__'  => 'Not set',
                    ],
                    'value' => function($m){
                        return match($m->contract_type){
                            '1y'  => '1 year',
                            '2y'  => '2 years',
                            '3y'  => '3 years',
                            'm2m' => 'Month-to-month',
                            null, '' => 'Not set',
                            default => $m->contract_type,
                        };
                    },
                ],



                      
            //   [
            //     'attribute'=>'phone_alt',
            //     'label'=>'Alt. Phone',
            //     'value'=>fn(User $m)=> $m->profile?->phone_alt,
            //     'filter'=>Html::activeTextInput($searchModel,'phone_alt',['class'=>'form-control','placeholder'=>'Search…']),
            // ],
             
                    
                      
                    
            
        
                   
                    [
                      'class'=>'webvimark\components\StatusColumn',
                      'attribute'=>'email_confirmed',
                      'visible'=>User::hasPermission('viewUserEmail'),
                    ],
                    
             
        
					  
					
     
       
        
           
             
          [
                      'class'=>'webvimark\components\StatusColumn',
                      'attribute'=>'superadmin',
                      'visible'=>Yii::$app->user->isSuperadmin,
                    ],
                    [
                      'attribute'=>'gridRoleSearch',
                      'filter'=>ArrayHelper::map(
                         Role::getAvailableRoles(Yii::$app->user->isSuperAdmin),
                         'name','description'
                       ),
                      'value'=>fn(User $m)=> implode(', ', ArrayHelper::map($m->roles,'name','description')),
                      'format'=>'raw',
                      'visible'=>User::hasPermission('viewUserRoles'),
                    ],
                    [
                      'class'=>'webvimark\components\StatusColumn',
                      'attribute'=>'status',
                      'optionsArray'=>[
                        [User::STATUS_ACTIVE,   UserManagementModule::t('back','Active'),   'success'],
                        [User::STATUS_INACTIVE, UserManagementModule::t('back','Inactive'), 'warning'],
                        [User::STATUS_BANNED,   UserManagementModule::t('back','Banned'),   'danger'],
                      ],
                    ],
                    ['class'=>'yii\grid\CheckboxColumn','options'=>['style'=>'width:10px']],
                    ['class'=>'yii\grid\ActionColumn','contentOptions'=>['style'=>'width:70px;text-align:center']],
                ],
            ]) ?>

              </div>

              <!-- hamesha bottom pe visible horizontal scrollbar -->
<div id="grid-xbar" class="grid-xbar"><div class="grid-xbar-inner"></div></div>

            <?php Pjax::end() ?>

        </div>
    </div>
</div>

 

 
<?php
$this->registerCss(<<<CSS
 
.grid-wrap{
  position: relative;
  overflow: visible;
}

 
.user-index{ padding-bottom: 40px; }

 
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
  var scroller  = container ? container.querySelector('.gridview.table-responsive') : null; // <-- actual horizontal scroller
  var xbar      = document.getElementById('grid-xbar');
  var inner     = xbar ? xbar.querySelector('.grid-xbar-inner') : null;

  if(!container || !scroller || !xbar || !inner){ return; }

  // spacer width = table ki pooled width
  function syncWidths(){
    inner.style.width = scroller.scrollWidth + 'px';
  }

  // dono bars ko sync rakho
  function syncToXbar(){ xbar.scrollLeft = scroller.scrollLeft; }
  function syncToGrid(){ scroller.scrollLeft = xbar.scrollLeft; }

  // overlay bar ko viewport me table ke visible hisse ke saath align karo
  function positionBar(){
    var r = scroller.getBoundingClientRect();

    // agar grid viewport me nahi, bar chupao
    var onScreen = r.bottom > 0 && r.top < window.innerHeight;
    xbar.style.display = onScreen ? 'block' : 'none';
    if(!onScreen) return;

    // left & width = table ka visible area (sidebar/margins ko auto handle)
    var left  = Math.max(0, r.left);
    var right = Math.min(window.innerWidth, r.right);
    var width = Math.max(0, right - left);

    xbar.style.left  = left + 'px';
    xbar.style.width = width + 'px';
  }

  // clean old listeners
  if(scroller._sync){ scroller.removeEventListener('scroll', scroller._sync); }
  if(xbar._sync){     xbar.removeEventListener('scroll', xbar._sync); }
  if(window._gridXbarResize){ window.removeEventListener('resize', window._gridXbarResize); }
  if(window._gridXbarScroll){ window.removeEventListener('scroll', window._gridXbarScroll); }

  // attach fresh
  scroller._sync = syncToXbar;
  xbar._sync     = syncToGrid;
  scroller.addEventListener('scroll', scroller._sync);
  xbar.addEventListener('scroll',     xbar._sync);

  window._gridXbarResize = function(){ syncWidths(); positionBar(); };
  window._gridXbarScroll = function(){ positionBar(); };
  window.addEventListener('resize', window._gridXbarResize, {passive:true});
  window.addEventListener('scroll', window._gridXbarScroll, {passive:true});

  // initial
  syncWidths();
  syncToXbar();
  positionBar();
}

// first render
bindGridXbar();

// PJAX reload ke baad dubara bind
$(document).on('pjax:end', function(e){
  if(e.target && e.target.id === 'user-grid-pjax'){
    setTimeout(bindGridXbar, 0);
  }
});
JS);

