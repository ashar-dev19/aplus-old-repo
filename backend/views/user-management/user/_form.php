<?php
use webvimark\modules\UserManagement\models\User;
use webvimark\modules\UserManagement\UserManagementModule;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use webvimark\extensions\BootstrapSwitch\BootstrapSwitch;
use yii\helpers\ArrayHelper;
use webvimark\modules\UserManagement\models\rbacDB\Role;

// === IMPORTANT: apne project ke mutabiq models ke namespaces ===
use frontend\models\Grade;   // Grades yahan se aati hain (title column)
use backend\models\Student;  // Inline student rows ke liye

/**
 * @var yii\web\View                                      $this
 * @var webvimark\modules\UserManagement\models\User      $model
 * @var backend\modules\UserManagement\models\UserProfile $profile
 * @var backend\models\Student[]                          $students
 * @var bool                                              $allowStudentInline  // (optional) controller se pass karein
 */

// Fallbacks
$allowStudentInline = $allowStudentInline ?? $model->isNewRecord;
if (!isset($students) || !is_array($students) || empty($students)) {
    $students = [new Student()];
}

$roleParam        = Yii::$app->request->get('user_role');
$isPredefinedRole = !empty($roleParam);

$rolesArray = ArrayHelper::map(
    Role::find()->select(['name', 'description'])->asArray()->all(),
    'name',
    'description'
);

// Grades for dropdown (title column)
$gradeList = ArrayHelper::map(
    Grade::find()->select(['id', 'title'])->orderBy('title')->asArray()->all(),
    'id',
    'title'
);
?>

<div class="user-form">
    <?php
// default country for new user
if ($model->isNewRecord && empty($profile->country)) {
    $profile->country = 'Canada';
}
?>


    <?php $form = ActiveForm::begin([
        'id'             => 'user',
        'layout'         => 'horizontal',
        'validateOnBlur' => false,
        'options'        => ['enctype' => 'multipart/form-data'], // file upload required
    ]); ?>

    <?php $model->loadDefaultValues(); ?>

    <?= Html::activeHiddenInput($model, 'status', ['value' => User::STATUS_ACTIVE]) ?>

    <?= $form->field($model, 'username')->textInput([
        'maxlength'    => 255,
        'autocomplete' => 'off',
    ]) ?>

    <?php if ($model->isNewRecord): ?>
        <?= $form->field($model, 'password')->passwordInput(['maxlength' => 255, 'autocomplete'=>'off']) ?>
        <?= $form->field($model, 'repeat_password')->passwordInput(['maxlength' => 255, 'autocomplete'=>'off']) ?>
    <?php endif; ?>
 

    
<?php if (!$model->isNewRecord): ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Password (hashed)</label>

        <div class="col-sm-10">
            <!-- 1) Current hash sirf dikhane ke liye (disabled), submit nahi hoga -->
            <input type="text"
                   class="form-control mb-2"
                   value="<?= Html::encode($model->password_hash) ?>"
                   disabled>

            <!-- 2) User yahan kuch likhe to naya password manenge (OPTIONAL) -->
            <input type="text"
                   id="newPasswordOptional"
                   class="form-control"
                   placeholder="Leave blank to keep the same password">

            <!-- 3) REAL hidden field jo submit hoga; JS isey fill karega sirf tab jab upar kuch likha ho -->
            <input type="hidden" name="User[password]" id="User_password_real" value="">
        </div>
    </div>

    <?php
    // Submit pe: agar newPasswordOptional me kuch likha ho to hidden me copy; warna empty rehne do
    $this->registerJs(<<<JS
    (function($){
      $('#user').on('submit', function(){
        var val = $.trim($('#newPasswordOptional').val() || '');
        $('#User_password_real').val(val); // blank hua to password change nahi hoga
      });
    })(jQuery);
    JS);
    ?>
<?php endif; ?>



 




    <?php if (User::hasPermission('editUserEmail')): ?>
        <?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>
        <?= $form->field($model, 'email_confirmed')->checkbox() ?>
    <?php endif; ?>

     <?= $form->field($model, 'fname')->textInput(['maxlength' => 64]) ?>
    <?= $form->field($model, 'lname')->textInput(['maxlength' => 64]) ?>
<?= $form->field($model, 'phone')->textInput(['maxlength' => 15]) ?>
 <?= $form->field($profile, 'unit_number')->textInput(['maxlength' => 64]) ?>

    <!-- Extra fields -->
    <?= $form->field($model, 'address')->textarea(['rows' => 1, 'maxlength' => true]) ?>
    
 <?= $form->field($profile, 'city')->textInput(['maxlength' => 128]) ?>
 <?= $form->field($profile, 'province')->textInput(['maxlength' => 64]) ?>
   <?= $form->field($profile, 'postal_code')->textInput(['maxlength' => 16]) ?>
     <?= $form->field($profile, 'country')->textInput(['maxlength' => 64]) ?>



   

    <?= $form->field($profile, 'parent_firstname')->textInput(['maxlength' => 64]) ?>
    <?= $form->field($profile, 'parent_lastname')->textInput(['maxlength' => 64]) ?>
    <!-- ==== PROFILE FIELDS ==== -->
    

    <?php
// family_name visible field mat dikhao, iski jagah ye hidden input rakho:
echo Html::hiddenInput(
    Html::getInputName($profile, 'family_name'),
    $profile->family_name ?: 'N/A'   // ya '' agar empty allow hai
);
?>

   
    
   
  
    <?= $form->field($profile, 'email_alternate')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($profile, 'phone_alt')->textInput(['maxlength' => 32]) ?>

      <?php
    // Sales person dropdown (role: sales-person)
    // echo $form->field($model, 'sales_person')->inline(true)->dropdownList(
    //     ArrayHelper::map(
    //         \webvimark\modules\UserManagement\models\User::find()
    //             ->select(['user.id', 'user.username'])
    //             ->leftJoin('rbac_auth_assignment role', 'user.id = role.user_id')
    //             ->andWhere(['role.item_name' => 'sales-person'])
    //             ->orderBy(['user.username' => SORT_ASC])
    //             ->all(),
    //         'id',
    //         'username'
    //     ),
    //     ['prompt' => 'Select sales person']
    // );
    ?>

    <?php
         
        // echo $form->field($model, 'contract_type')->dropDownList([
        // '1y'  => '1 year contract',
        // '2y'  => '2 year contract',
        // '3y'  => '3 year contract',
        // 'm2m' => 'Month-to-month',
        // 'test' => '2 Min Expiry Test Contract',
        // ], ['prompt' => 'Select contract']);

          echo $form->field($model, 'contract_type')->dropDownList([
        '1y'  => '1 year contract',
        '2y'  => '2 year contract',
        '3y'  => '3 year contract',
        'm2m' => 'Month-to-month',
        ], ['prompt' => 'Select contract'])
        ->label("Contract Duration");

    ?>

    <?php
         
        echo $form->field($model, 'sales_person')->inline(true)->dropdownList(
            ArrayHelper::map(
                \webvimark\modules\UserManagement\models\User::find()
                    ->alias('u')
                    ->leftJoin('rbac_auth_assignment role', 'u.id = role.user_id')
                    ->select([
                        'u.id',
                        'u.username',
                        'u.email',
                        // full_name: fname + lname (trim so extra spaces na ayen)
                        new \yii\db\Expression("TRIM(CONCAT(COALESCE(u.fname,''),' ',COALESCE(u.lname,''))) AS full_name"),
                    ])
                    ->andWhere(['role.item_name' => 'sales-person'])
                    ->orderBy([
                        // jin ke fname empty hain unhein baad me rakho
                        new \yii\db\Expression("CASE WHEN (u.fname IS NULL OR u.fname='') THEN 1 ELSE 0 END"),
                        'u.fname' => SORT_ASC,
                        'u.lname' => SORT_ASC,
                    ])
                    ->asArray()
                    ->all(),
                'id',
                function ($row) {
                    // Label priority: "Fname Lname" -> username -> email
                    $label = trim((string)$row['full_name']);
                    if ($label === '') {
                        $label = $row['username'] ?: ($row['email'] ?: 'Unknown');
                    }
                    return $label;
                }
            ),
            ['prompt' => 'Select sales person']
        );
    ?>


    <?php if (User::hasPermission('assignRolesToUsers')): ?>
        <div class="form-group">
            <?php
            $role = Yii::$app->request->post('user_role') ?: Yii::$app->request->get('user_role');
            if ($model->isNewRecord) {
                $roleName = ($role != '' ? $role : '');
            } else {
                $roleName = isset($model->roles[0]) ? $model->roles[0]->name : '';
            }
            ?>

            <?php if ($isPredefinedRole): ?>
                <?= $form->field($model, 'user_role')->dropDownList(
                    [$roleParam => ucfirst(str_replace('-', ' ', $roleParam))],
                    ['readonly' => true, 'disabled' => true]
                ) ?>
                <?= Html::hiddenInput('User[user_role]', $roleParam) ?>
            <?php else: ?>
                <?php
                $currentRole = isset($model->roles[0]) ? $model->roles[0]->name : null;
                echo $form->field($model, 'user_role')->dropDownList(
                    $rolesArray,
                    [
                        'prompt'  => 'Select Role',
                        'options' => [$currentRole => ['Selected' => true]],
                    ]
                );
                ?>
            <?php endif; ?>
        </div>

        <?= $form->errorSummary($model) ?>
        <?= $form->errorSummary($profile) ?>
    <?php endif; ?>

    <?php if ($allowStudentInline): ?>
        <hr>
        <h4>Add Students</h4>

        <table class="table table-bordered" id="students-table">
            <thead>
             
                <tr>
                    <th style="width:35%">Full Name</th>
                     <th style="width:20%">Gender</th>
                    <th style="width:18%">Grade</th>
                    <th style="width:22%">Date of Birth</th>
                   
                    <th style="width:5%">Action</th>
                </tr>

            </thead>
            <tbody>
                <?php foreach ($students as $i => $s): ?>
                    <tr>
  <td>
    <?= Html::textInput("Student[$i][full_name]", $s->full_name, [
        'class'=>'form-control','placeholder'=>'Student name'
    ]) ?>
    <?= Html::hiddenInput("Student[$i][status]", $s->status ?? 1) ?>
  </td>

   <td>
    <?= Html::dropDownList("Student[$i][gender]", (string)($s->gender ?? ''), [
        '1' => 'Male',
        '2' => 'Female',
        '0' => 'Other',
    ], ['class'=>'form-control','prompt'=>'Select gender']) ?>
  </td>

  <td>
    <?= Html::dropDownList("Student[$i][grade_id]", $s->grade_id, $gradeList, [
        'class'=>'form-control','prompt'=>'Select grade'
    ]) ?>
  </td>

  <td>
    <?= Html::input('date', "Student[$i][dob]", $s->dob ?? null, [
        'class'=>'form-control'
    ]) ?>
  </td>

 

  <td class="text-center">
    <button type="button" class="btn btn-sm btn-danger js-remove-row" title="Remove">&times;</button>
  </td>
</tr>

                <?php endforeach; ?>
            </tbody>
        </table>

        <button type="button" class="btn btn-secondary" id="js-add-student">+ Add another student</button>

        <?php
        
$template = Html::beginTag('tr') .
  '<td>'.
    Html::textInput('Student[__index__][full_name]', '', [
      'class'=>'form-control','placeholder'=>'Student name'
    ]) .
    Html::hiddenInput('Student[__index__][status]', 1) .
  '</td>' .
  
  '<td>'.
    Html::dropDownList('Student[__index__][gender]', null, [
      '1'=>'Male','2'=>'Female','0'=>'Other'
    ], ['class'=>'form-control','prompt'=>'Select gender']) .
  '</td>' .


    '<td>'.
    Html::dropDownList('Student[__index__][grade_id]', null, $gradeList, [
      'class'=>'form-control','prompt'=>'Select grade'
    ]) .
  '</td>' .


  '<td>'.
    Html::input('date', 'Student[__index__][dob]', null, ['class'=>'form-control']) .
  '</td>' .




  '<td class="text-center"><button type="button" class="btn btn-sm btn-danger js-remove-row">&times;</button></td>' .
Html::endTag('tr');
 


        $this->registerJs(<<<JS
(function(){
  var idx = $("#students-table tbody tr").length;
  var tpl = `$template`.trim();

  $("#js-add-student").on("click", function(){
    var row = tpl.replace(/__index__/g, idx);
    $("#students-table tbody").append(row);
    idx++;
  });

  $("#students-table").on("click", ".js-remove-row", function(){
    var rows = $("#students-table tbody tr");
    if (rows.length > 1) {
      $(this).closest("tr").remove();
    } else {
      // last row ko clear kar do
      var tr = $(this).closest("tr");
      tr.find("input[type=text]").val('');
      tr.find("select").val('');
      tr.find("input[type=file]").val('');
    }
  });
})();
JS);
        ?>
    <?php else: ?>
        <!-- Update view par students section hide. Agar chaho to note dikha do: -->
        <!--
        <hr>
        <div class="alert alert-info mb-3">
            Students ko add/update karna ho to Students module par jao.
        </div>
        -->
    <?php endif; ?>

    <div class="form-group mt-3">
        <div class="col-sm-offset-3 col-sm-9">
            <?php if ($model->isNewRecord): ?>
                <?= Html::submitButton(
                    '<span class="glyphicon glyphicon-plus-sign"></span> ' . UserManagementModule::t('back', 'Create'),
                    ['class' => 'btn btn-success']
                ) ?>
            <?php else: ?>
                <?= Html::submitButton(
                    '<span class="glyphicon glyphicon-ok"></span> ' . UserManagementModule::t('back', 'Save'),
                    ['class' => 'btn btn-primary']
                ) ?>
            <?php endif; ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php BootstrapSwitch::widget() ?>
