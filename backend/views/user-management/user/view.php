<?php

use webvimark\modules\UserManagement\components\GhostHtml;
use webvimark\modules\UserManagement\models\rbacDB\Role;
use webvimark\modules\UserManagement\models\User;
use webvimark\modules\UserManagement\UserManagementModule;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap4\ActiveForm;       
use frontend\models\Grade;

use webvimark\modules\UserManagement\models\User as U;
 
use yii\grid\GridView;
use yii\helpers\Url;

 
 

/**
 * @var yii\web\View $this
 * @var webvimark\modules\UserManagement\models\User $model
 */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => UserManagementModule::t('back', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

 


$expired = (function($m){
    // m2m kabhi expire nahi hota
    if ($m->contract_type === 'm2m') return false;

    $created = (int)$m->created_at;
    if ($created <= 0) return false;

    $now = time();

    // test => 2 minutes
    if ($m->contract_type === 'test') {
        return $now >= ($created + 120);
    }

    // years
    $years = 3;
    if ($m->contract_type === '1y') $years = 1;
    elseif ($m->contract_type === '2y') $years = 2;
    elseif ($m->contract_type === '3y') $years = 3;

    $endTs = strtotime("+{$years} years", $created);
    return $now >= $endTs;
})($model);
 


?>
<div class="user-view">

	<h2 class="lte-hide-title"><?= $this->title ?></h2>

	<div class="panel panel-default">
		<div class="panel-body">

		    <p>
			<?php //echo GhostHtml::a(UserManagementModule::t('back', 'Edit'), ['update', 'id' => $model->id], ['class' => 'btn btn-sm btn-primary']) ?>
			<?php //echo GhostHtml::a(UserManagementModule::t('back', 'Create'), ['create'], ['class' => 'btn btn-sm btn-success']) ?>
			<?php
			// echo GhostHtml::a(
			// 	UserManagementModule::t('back', 'Roles and permissions'),
			// 	['/user-management/user-permission/set', 'id'=>$model->id],
			// 	['class' => 'btn btn-sm btn-default']
			// ) 
			?>

			<?php //echo  GhostHtml::a(UserManagementModule::t('back', 'Delete'), ['delete', 'id' => $model->id], [
			    // 'class' => 'btn btn-sm btn-danger pull-right',
			    // 'data' => [
				// 'confirm' => UserManagementModule::t('back', 'Are you sure you want to delete this user?'),
				// 'method' => 'post',
			    // ],
			// ])
			 ?>

			 
 <p>
    <?php
    // Edit / Create
    echo GhostHtml::a(UserManagementModule::t('back', 'Edit'), ['update', 'id' => $model->id], ['class' => 'btn btn-sm btn-primary']);
    echo ' ';
    echo GhostHtml::a(UserManagementModule::t('back', 'Create'), ['create'], ['class' => 'btn btn-sm btn-success']);
    ?>

    <?php if ((int)$model->status === U::STATUS_ACTIVE): ?>

        <!-- ACTIVE => sirf Deactivate button -->
        <?= GhostHtml::a('Deactivate', ['deactivate', 'id' => $model->id], [
            'class' => 'btn btn-sm btn-warning',
            'data'  => ['confirm' => 'Deactivate this user and all their students?', 'method' => 'post'],
        ]) ?>

    <?php else: ?>

        <?php if ($expired): ?>
            <!-- INACTIVE + EXPIRED => Renew dropdown + Activate -->
            <?php
            echo Html::beginForm(['activate', 'id' => $model->id], 'post', [
                'style' => 'display:inline-block;margin-left:10px;'
            ]);
            echo Html::dropDownList(
                'contract_type',
                $model->contract_type,
                [
                    'test' => 'Test (2 min)',
                    '1y'   => '1 Year',
                    '2y'   => '2 Years',
                    '3y'   => '3 Years',
                    'm2m'  => 'Month-to-Month',
                ],
                ['class' => 'form-control', 'style' => 'display:inline-block;width:auto;']
            );
            echo ' ';
            echo Html::submitButton('Activate & Assign Contract', ['class' => 'btn btn-success']);
            echo Html::endForm();
            ?>
        <?php else: ?>
            <!-- INACTIVE + NOT EXPIRED (ya manually inactive) => simple Activate -->
            <?= GhostHtml::a('Activate', ['activate', 'id' => $model->id], [
                'class' => 'btn btn-sm btn-success',
                'data'  => ['confirm' => 'Activate this user and all their students?', 'method' => 'post'],
            ]) ?>
        <?php endif; ?>

    <?php endif; ?>
</p>

 



   

	


		    </p>

			<?= DetailView::widget([
				'model'      => $model,
				'attributes' => [

					// 'id',
					// [
					// 	'attribute'=>'status',
					// 	'value'=>User::getStatusValue($model->status),
					// ],
					'username',
					[
						'attribute'=>'email',
						'value'=>$model->email,
						'format'=>'email',
						'visible'=>User::hasPermission('viewUserEmail'),
					],
					// [
					// 	'attribute'=>'email_confirmed',
					// 	'value'=>$model->email_confirmed,
					// 	'format'=>'boolean',
					// 	'visible'=>User::hasPermission('viewUserEmail'),
					// ],
					// [
					// 	'label' => 'Password (hash)',
					// 	'value' => $model->password_hash,
					// 	'visible' => (Yii::$app->user->identity->username === 'superadmin' && !$model->superadmin),
					// ],
					[
						'label'=>UserManagementModule::t('back', 'Role'),
						'value'=>implode('<br>', ArrayHelper::map(Role::getUserRoles($model->id), 'name', 'description')),
						'visible'=>User::hasPermission('viewUserRoles'),
						'format'=>'raw',
					],
					// [
					// 	'attribute'=>'bind_to_ip',
					// 	'visible'=>User::hasPermission('bindUserToIp'),
					// ],
					// array(
					// 	'attribute'=>'registration_ip',
					// 	'value'=>Html::a($model->registration_ip, "http://ipinfo.io/" . $model->registration_ip, ["target"=>"_blank"]),
					// 	'format'=>'raw',
					// 	'visible'=>User::hasPermission('viewRegistrationIp'),
					// ),

					// 'created_at:datetime',
					// 'updated_at:datetime',
				],

			]) ?>

		</div>
	</div>
</div>
 

 





<hr>
<br>

<div class="panel panel-default">
  <h2>Students</h2>
  <div class="panel-body">
    <?= GridView::widget([
      'dataProvider' => $studentsProvider,
      'summary' => '',
      'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        // 'id',
        'full_name',
		[
			'attribute' => 'grade_id',
			'label' => 'Grade',
			'value' => function($m){
				
				return $m->grade->name ?? $m->grade->title ?? '-';
			},
			'format' => 'text', 
		],

        // [
        //   'attribute' => 'details',
        //   'label' => 'File',
        //   'format' => 'raw',
        //   'value' => function($m){
        //     if (!$m->details) return '-';
        //     $url = Yii::getAlias('@web/uploads/') . $m->details;
        //     return Html::a('Download', $url, ['target' => '_blank']);
        //   }
        // ],
        // [
        //   'attribute' => 'created_at',
        //   'format' => ['datetime'],
        // ],
		
			[
			'class' => 'yii\grid\ActionColumn',
			'header' => 'Actions',
			'template' => '{edit} {delete}',
			'contentOptions' => ['style'=>'width:120px;text-align:center'],
			'buttons' => [
				'edit' => function($url, $m) {
				return Html::a('<i class="fa-fw fas fa-edit"></i>', '#', [
					'class'       => 'btn btn-sm btn-outline-primary js-edit-student',
					'title'       => 'Edit',
					'data-id'     => $m->id,
					'data-name'   => $m->full_name,
					'data-gender' => (string)$m->gender,
					'data-grade'  => (string)$m->grade_id,
					'data-dob'    => $m->dob,
				]);
				},
				'delete' => function($url, $m) use ($model) {
				return Html::a('<i class="fa-fw fas fa-trash"></i>',
					['delete-student-inline', 'id'=>$model->id, 'sid'=>$m->id],
					[
					'class' => 'btn btn-sm btn-outline-danger',
					'title' => 'Delete',
					'data-confirm' => 'Delete this student?',
					'data-method'  => 'post',
					]
				);
				},
			],
			],



      ],
	 

	  
      'emptyText' => 'No students assigned.',
    ]); ?>
  </div>
</div>


<hr>
<br>

<?php
// Grades for dropdown
$gradeList = ArrayHelper::map(
    Grade::find()->select(['id','title'])->orderBy('title')->asArray()->all(),
    'id', 'title'
);
?>

 
	<div class="panel panel-default" id="add-student-inline" style="margin-top:10px;">
   <h2>Add Student</h2>
  <div class="panel-body">
    <?php $form = ActiveForm::begin([
        'action' => ['add-student-inline', 'id' => $model->id],
        'method' => 'post',
        'options' => ['class' => 'form-inline'],
    ]); ?>

      <div class="form-row" style="gap:10px; width:100%; align-items:flex-end;">
        <div class="form-group" style="min-width:260px;">
          <label class="control-label">Full Name</label>
          <input type="text" name="Student[full_name]" class="form-control" placeholder="Student name" required>
        </div>

        <div class="form-group">
          <label class="control-label">Gender</label>
          <select name="Student[gender]" class="form-control">
            <option value="">Select gender</option>
            <option value="1">Male</option>
            <option value="2">Female</option>
            <option value="0">Other</option>
          </select>
        </div>

        <div class="form-group">
          <label class="control-label">Grade</label>
          <select name="Student[grade_id]" class="form-control">
            <option value="">Select grade</option>
            <?php foreach ($gradeList as $gid => $gtitle): ?>
              <option value="<?= (int)$gid ?>"><?= Html::encode($gtitle) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label class="control-label">Date of Birth</label>
          <input type="date" name="Student[dob]" class="form-control">
        </div>

        <!-- hidden defaults -->
        <input type="hidden" name="Student[parent_id]" value="<?= (int)$model->id ?>">
        <input type="hidden" name="Student[status]" value="1">

        <div class="form-group">
          <button type="submit" class="btn btn-success">Add</button>
        </div>
      </div>

    <?php ActiveForm::end(); ?>
  </div>
</div>



<div class="modal fade" id="studentEditModal" tabindex="-1" role="dialog" aria-labelledby="studentEditLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="studentEditLabel">Edit Student</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
      </div>

      <?php $form = \yii\bootstrap4\ActiveForm::begin([
        'action' => ['edit-student-inline', 'id' => $model->id],
        'method' => 'post',
        'options' => ['id' => 'student-edit-form'],
      ]); ?>

      <div class="modal-body">
        <input type="hidden" name="Student[id]" id="st-id">
        <div class="form-group">
          <label>Full Name</label>
          <input type="text" name="Student[full_name]" id="st-name" class="form-control" required>
        </div>

        <div class="form-group">
          <label>Gender</label>
          <select name="Student[gender]" id="st-gender" class="form-control">
            <option value="">Select gender</option>
            <option value="1">Male</option>
            <option value="2">Female</option>
            <option value="0">Other</option>
          </select>
        </div>

        <div class="form-group">
          <label>Grade</label>
          <select name="Student[grade_id]" id="st-grade" class="form-control">
            <option value="">Select grade</option>
            <?php foreach ($gradeList as $gid => $gtitle): ?>
              <option value="<?= (int)$gid ?>"><?= Html::encode($gtitle) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label>Date of Birth</label>
          <input type="date" name="Student[dob]" id="st-dob" class="form-control">
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Save</button>
      </div>

      <?php \yii\bootstrap4\ActiveForm::end(); ?>
    </div>
  </div>
</div>


<?php
$this->registerJs(<<<JS
$(document).on('click', '.js-edit-student', function(e){
  e.preventDefault();
  var b = $(this);
  $('#st-id').val(b.data('id'));
  $('#st-name').val(b.data('name') || '');
  $('#st-gender').val(b.data('gender') || '');
  $('#st-grade').val(b.data('grade') || '');
  var dob = (b.data('dob') || '').substring(0,10);
  $('#st-dob').val(dob);
  $('#studentEditModal').modal('show');
});
JS);
?>



 <style>
	#add-student-inline {
    padding: 21px 15px;
    border: 1px solid #dee2e6;
}

#add-student-inline .panel-body {
    display: flex;
    align-items: center;
    /* justify-content: center; */
}

#add-student-inline form {
    display: flex;
    flex-direction: column;
	    width: 100% !important;
}

#add-student-inline .form-group {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    width: 24%;
}

#add-student-inline .form-row {
    flex-wrap: wrap;
}

#add-student-inline input.form-control,
#add-student-inline select.form-control {
    width: 100%;
}

.sales_assigned{
	    padding: 21px 15px;
    border: 1px solid #dee2e6;
 
}
 </style>



<hr>
<br>

<div class="panel panel-default sales_assigned">
  <h2>Sales rep assigned to them</h2>
  <div class="panel-body">
    <?php if ($model->salesPerson): ?>
      <p>
        <strong><?= Html::encode($model->salesPerson->username) ?></strong>
        <?php if ($model->salesPerson->email): ?>
          &middot; <?= Html::a(Html::encode($model->salesPerson->email), 'mailto:' . $model->salesPerson->email) ?>
        <?php endif; ?>
        &middot; <?= Html::a('View', ['sales-person-view', 'id' => $model->salesPerson->id], ['class' => 'btn btn-xs btn-default']) ?>
      </p>
    <?php else: ?>
      <em>No sales person assigned.</em>
    <?php endif; ?>
  </div>
</div>
 

<?php
// Sirf tab dikhayein jab yeh user Sales Person ho (ya aap chahein to hamesha dikha dein)
$showAssignedUsers = method_exists($model, 'isSalesPerson') ? $model->isSalesPerson() : \webvimark\modules\UserManagement\models\User::hasRole(['sales-person']);
?>
