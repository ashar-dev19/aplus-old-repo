<?php
namespace backend\modules\UserManagement\controllers;

use webvimark\components\AdminDefaultController;
use Yii;
use yii\base\Model;
use webvimark\modules\UserManagement\models\User;
// use webvimark\modules\UserManagement\models\search\UserSearch;
// use backend\modules\UserManagement\models\ParentUser as User;
use webvimark\modules\UserManagement\models\rbacDB\AuthAssignment;
use backend\modules\UserManagement\models\ParentUser;
use backend\modules\UserManagement\models\UserProfile;
// use backend\modules\UserManagement\models\search\UserSearch;

use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use yii\data\ActiveDataProvider;



use yii\web\UploadedFile;
use backend\models\Student;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends AdminDefaultController
{
    public $modelClass       = 'backend\modules\UserManagement\models\ParentUser';
    
    public $modelSearchClass = 'backend\modules\UserManagement\models\search\UserSearch';
    // public $modelSearchClass = 'webvimark\modules\UserManagement\models\search\UserSearch';
        
    public function actionClearCache()
    {
        Yii::$app->cache->flush(); 
        Yii::$app->db->schema->refresh();
        
        echo "Cache cleared and schema refreshed.";
        Yii::$app->end();
    }


    /**
     * Lists all models.
     * @return mixed
     */
    public function actionIndex()
    {

    //     $model = \webvimark\modules\UserManagement\models\User::findOne(3716); 
    // var_dump($model->address);
    // var_dump($model->phone);
    // var_dump($model->sales_person);
    // var_dump($model->email);
    // exit;  
    
    $searchModel  = $this->modelSearchClass ? new $this->modelSearchClass : null;

        if ($searchModel) {
            $queryParams = array_merge([], Yii::$app->request->getQueryParams());
            !isset($queryParams["UserSearch"]["status"]) || ($queryParams["UserSearch"]["status"] === "")
                ? $queryParams["UserSearch"]["status"] = 1
                : $queryParams["UserSearch"]["status"];
            // $queryParams["UserSearch"]["company_id"] =
            //     !Yii::$app->user->isSuperadmin
            //     ? Yii::$app->user->profile->company->id
            //     : "";

            $dataProvider = $searchModel->search($queryParams);
        } else {
            $modelClass   = $this->modelClass;
            $dataProvider = new ActiveDataProvider([
                'query' => $modelClass::find(),
            ]);
        }

        return $this->renderIsAjax('index', compact('dataProvider', 'searchModel'));
    }


    
    

    // public function actionContractPreviewOne($id)
    // {
    //     $user = \webvimark\modules\UserManagement\models\User::findOne($id);
    //     if (!$user) {
    //         throw new \yii\web\NotFoundHttpException('User not found.');
    //     }

    //     // 1970-wale ko preview me bhi highlight do
    //     $tooOld = ((int)$user->created_at > 0 && (int)$user->created_at < 315532800); // < 1980-01-01

    //     $expired = method_exists($user, 'isContractExpired') ? $user->isContractExpired() : false;

    //     $msg  = "User #{$user->id} ({$user->username})<br>";
    //     $msg .= "Created at: " . ($user->created_at ? date('Y-m-d H:i:s', $user->created_at) : 'N/A') . "<br>";
    //     $msg .= "Contract: " . ($user->contract_type ?: 'Not set (defaults 3y)') . "<br>";
    //     $msg .= "Expired? <b>" . ($expired ? 'YES' : 'NO') . "</b><br>";
    //     if ($tooOld) {
    //         $msg .= "<span style='color:#c00'>Note: created_at looks too old; batch jobs should skip this user.</span>";
    //     }

    //     return $this->renderContent($msg);
    // }

    
public function actionContractApplyOne($id)
{
    $user = \webvimark\modules\UserManagement\models\User::findOne($id);
    if (!$user) {
        throw new \yii\web\NotFoundHttpException('User not found.');
    }

    // Check if the contract has expired
    $expired = method_exists($user, 'isContractExpired') ? $user->isContractExpired() : false;

   if ($expired) {
    $tx = Yii::$app->db->beginTransaction();
    try {
        // Log to see if the status change is attempted
        Yii::info("Updating user status to INACTIVE for user ID: {$user->id}", __METHOD__);
        
        $user->status = \webvimark\modules\UserManagement\models\User::STATUS_INACTIVE;
        if ($user->save(false)) {
            Yii::info("User status updated to INACTIVE.", __METHOD__);
        } else {
            Yii::error("Failed to update user status.", __METHOD__);
        }
        \backend\models\Student::updateAll(['status' => 0], ['parent_id' => $user->id]);
        $tx->commit();
        Yii::$app->session->setFlash('success', 'User expired => deactivated (students also set inactive).');
    } catch (\Throwable $e) {
        $tx->rollBack();
        Yii::$app->session->setFlash('error', $e->getMessage());
    }
} else {
    Yii::$app->session->setFlash('info', 'Not expired — no change made.');
}


    return $this->redirect(['view', 'id' => $id]);
}





    public function actionView($id)
    {
        /** @var \webvimark\modules\UserManagement\models\User $model */
        $model = User::find()
            ->where(['id' => $id])
            ->with(['salesPerson', 'assignedUsers', 'students']) // your relations
            ->one();

        if (!$model) {
            throw new \yii\web\NotFoundHttpException('User not found.');
        }

        $studentsProvider = new \yii\data\ActiveDataProvider([
            'query' => \backend\models\Student::find()->where([
                'parent_id' => $model->id,
                'status'    => 1,  
            ]),
            'pagination' => ['pageSize' => 10],
        ]);

        $assignedUsersProvider = new \yii\data\ActiveDataProvider([
            'query' => User::find()->where([
                'sales_person' => $model->id,
                'status'       => 1, 
            ]),
            'pagination' => ['pageSize' => 10],
        ]);


        return $this->renderIsAjax('view', [
            'model' => $model,
            'studentsProvider' => $studentsProvider,
            'assignedUsersProvider' => $assignedUsersProvider,
        ]);
    }


     
   

   // ✅ keep the same signature as parent
public function actionBulkActivate($attribute = 'active')
{
    // hum apna cascade chalana chahte hain, isliye yahan hamesha 'status' pass kar rahe
    $this->bulkToggleStatus('status', User::STATUS_ACTIVE);
}

public function actionBulkDeactivate($attribute = 'active')
{
    // yahan bhi 'status' hi
    $this->bulkToggleStatus('status', User::STATUS_INACTIVE);
}

    

    /**
     * Helper to flip $attribute to $value on all selected IDs.
     * @throws BadRequestHttpException
     */
    // protected function bulkToggleStatus(string $attribute, $value)
    // {
    //     $ids = Yii::$app->request->post('selection', []);

    //     if (empty($ids) || !$attribute) {
    //         throw new BadRequestHttpException('No items selected or attribute missing.');
    //     }

    //     $tx = Yii::$app->db->beginTransaction();
    //     try {
    //         User::updateAll([$attribute => $value], ['id' => $ids]);

    //         // Cascade only when we're changing status
    //         if ($attribute === 'status') {
    //             if ((int)$value === User::STATUS_INACTIVE) {
    //                 // parents -> inactive => students -> inactive
    //                 \backend\models\Student::updateAll(['status' => 0], ['parent_id' => $ids]);
    //             }
    //             // (Optional) activate par students bhi active:
    //             else if ((int)$value === User::STATUS_ACTIVE) {
    //                 \backend\models\Student::updateAll(['status' => 1], ['parent_id' => $ids]);
    //             }
    //         }

    //         $tx->commit();
    //     } catch (\Throwable $e) {
    //         $tx->rollBack();
    //         Yii::$app->session->setFlash('error', $e->getMessage());
    //     }

    //     return $this->redirect(['index']);
    // }


    protected function bulkToggleStatus(string $attribute, $value)
    {
        $ids = Yii::$app->request->post('selection', []);
        if (empty($ids) || !$attribute) {
            throw new BadRequestHttpException('No items selected or attribute missing.');
        }

        $tx = Yii::$app->db->beginTransaction();
        try {
            if ($attribute === 'status' && (int)$value === \webvimark\modules\UserManagement\models\User::STATUS_ACTIVE) {
                // ✅ activate + reset contract start
                \webvimark\modules\UserManagement\models\User::updateAll(
                    ['status' => 1, 'created_at' => time()], // reset here too
                    ['id' => $ids]
                );
                \backend\models\Student::updateAll(['status' => 1], ['parent_id' => $ids]);
            } else {
                // existing paths
                \webvimark\modules\UserManagement\models\User::updateAll([$attribute => $value], ['id' => $ids]);

                if ($attribute === 'status' && (int)$value === \webvimark\modules\UserManagement\models\User::STATUS_INACTIVE) {
                    \backend\models\Student::updateAll(['status' => 0], ['parent_id' => $ids]);
                }
            }

            $tx->commit();
        } catch (\Throwable $e) {
            $tx->rollBack();
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['index']);
    }



    

 
public function actionCreate()
{
    $model   = new User(['scenario' => 'newUser']);
    $profile = new UserProfile();
    $students = [new Student()];

    if ($model->load(Yii::$app->request->post()) && $profile->load(Yii::$app->request->post())) {

        
        $studentsPost = Yii::$app->request->post('Student', []);
        $students = [];
        foreach ($studentsPost as $i => $row) {
            $fullName = trim((string)($row['full_name'] ?? ''));
            $gradeId  = $row['grade_id'] ?? null;

          
            $fileInst = UploadedFile::getInstanceByName("Student[$i][details]");
            if ($fullName === '' && empty($gradeId) && $fileInst === null) {
                continue;
            }

            $s = new Student();
            $s->load(['Student' => $row]);
            $s->details = $fileInst;        
            $s->status  = 1;   
            $students[] = $s;
        }

        $tx = Yii::$app->db->beginTransaction();
        try {
           
            $uSchema = Yii::$app->db->schema->getTableSchema(User::tableName(), true);
            if ($uSchema && isset($uSchema->columns['last_login'])) {
                $col = $uSchema->columns['last_login'];
                if (!$col->allowNull && empty($model->last_login)) {
                    $model->last_login = 0;
                }
            }

            if (!$model->save()) {
                throw new \Exception('User save failed: '.json_encode($model->getFirstErrors()));
            }

            // sales_person, profile, role
            $model->sales_person = Yii::$app->request->post('User')['sales_person'] ?? null;
            $model->save(false);

            $profile->user_id = $model->id;
            $profile->save(false);

            $roleName = Yii::$app->request->post('User')['user_role'] ?? null;
            if ($roleName &&
                \webvimark\modules\UserManagement\models\rbacDB\Role::find()->where(['name'=>$roleName])->exists()) {
                User::assignRole($model->id, $roleName);
            }

           
            if (!empty($students)) {
                foreach ($students as $s) {
                    $s->parent_id = $model->id; 
                    if ($s->grade_id === '' || $s->grade_id === null) {
                        $s->grade_id = null;    
                    }
                }

                if (!Model::validateMultiple($students)) {
                    
                    $errs = [];
                    foreach ($students as $idx => $s) {
                        if ($s->hasErrors()) {
                            $errs["row $idx"] = $s->getFirstErrors();
                        }
                    }
                    throw new \Exception('Students validation failed: '.json_encode($errs));
                }

                foreach ($students as $i => $s) {
                     
                    if ($s->details instanceof UploadedFile) {
                        $fileName = Yii::$app->security->generateRandomString().'.'.$s->details->extension;
                        $absPath  = Yii::getAlias('@frontend/web/uploads/').$fileName;

                        if ($s->details->saveAs($absPath)) {
                           
                            $s->details = 'uploads/'.$fileName;
                        } else {
                            $s->details = null;
                        }
                    }
                    $s->save(false);  
                }
            }

            $tx->commit();
            Yii::$app->session->setFlash('success', 'User & students created.');
            return $this->redirect(['index']);

        } catch (\Throwable $e) {
            $tx->rollBack();
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
    }

    return $this->renderIsAjax('create', [
        'model'    => $model,
        'profile'  => $profile,
        'students' => $students,
        'allowStudentInline' => true,
    ]);
}


// function user ko active karne ka
public function actionDeactivate($id)
{
    $user = User::findOne($id);
    if (!$user) { throw new \yii\web\NotFoundHttpException('User not found'); }

    $tx = \Yii::$app->db->beginTransaction();
    try {
        // user inactive
        $user->status = User::STATUS_INACTIVE; // usually 0
        $user->save(false);

        // cascade students -> inactive
        \backend\models\Student::updateAll(['status' => 0], ['parent_id' => $user->id]);

        $tx->commit();
        \Yii::$app->session->setFlash('success', 'User and their students deactivated.');
    } catch (\Throwable $e) {
        $tx->rollBack();
        \Yii::$app->session->setFlash('error', $e->getMessage());
    }
    return $this->redirect(['view','id'=>$id]);
}


// // function user ko de active karne ka
// public function actionActivate($id)
// {
//     $user = User::findOne($id);
//     if (!$user) { throw new \yii\web\NotFoundHttpException('User not found'); }

//     $tx = \Yii::$app->db->beginTransaction();
//     try {
//         // user active
//         $user->status = User::STATUS_ACTIVE; // usually 1
//         $user->save(false);

//         // cascade students -> active
//         \backend\models\Student::updateAll(['status' => 1], ['parent_id' => $user->id]);

//         $tx->commit();
//         \Yii::$app->session->setFlash('success', 'User and their students activated.');
//     } catch (\Throwable $e) {
//         $tx->rollBack();
//         \Yii::$app->session->setFlash('error', $e->getMessage());
//     }
//     return $this->redirect(['view','id'=>$id]);
// }
 


public function actionActivate($id)
{
    $user = User::findOne($id);
    if (!$user) {
        throw new \yii\web\NotFoundHttpException('User not found');
    }

    $newContract = Yii::$app->request->post('contract_type', null);

    $tx = \Yii::$app->db->beginTransaction();
    try {
        $user->status     = User::STATUS_ACTIVE;
        $user->created_at = time(); // reset contract start
        if ($newContract) {
            $user->contract_type = $newContract; //assign new duration
        }
        $user->save(false, ['status', 'created_at', 'contract_type', 'updated_at']);

        \backend\models\Student::updateAll(['status' => 1], ['parent_id' => $user->id]);

        $tx->commit();
        Yii::$app->session->setFlash('success', 'User activated with new contract (' . ($newContract ?: 'unchanged') . ').');
    } catch (\Throwable $e) {
        $tx->rollBack();
        Yii::$app->session->setFlash('error', $e->getMessage());
    }

    return $this->redirect(['view', 'id' => $id]);
}







    
public function actionUpdate($id)
{
    $model   = User::findOne($id);
    $profile = UserProfile::findOne(['user_id' => $id]) ?: new UserProfile(['user_id' => $id]);

    if ($model->load(Yii::$app->request->post()) && $profile->load(Yii::$app->request->post())) {

         
        $postUser    = Yii::$app->request->post('User', []);
        $incomingPwd = isset($postUser['password']) ? trim((string)$postUser['password']) : '';

         
        $model->password = null;
        $model->repeat_password = null;

        
        if ($incomingPwd !== '') {
            $model->scenario        = 'changePassword';  
            $model->password        = $incomingPwd;
            $model->repeat_password = $incomingPwd;
        }

        if ($model->save()) { 
            $model->sales_person = $postUser['sales_person'] ?? null;
            $model->save(false);
 
            $profile->save(false);

           
            $oldRoles = $model->roles;
            foreach ($oldRoles as $role) {
                User::revokeRole($model->id, $role->name);
            }
            $newRole = $postUser['user_role'] ?? null;
            if ($newRole) {
                User::assignRole($model->id, $newRole);
            }

            return $this->redirect(['index']);
        }
    }

    return $this->renderIsAjax('update', [
        'model'   => $model,
        'profile' => $profile,
        'allowStudentInline' => false,
    ]);
}

   



    
    public function actionSalesPersons()
{
    
    $searchModel  = new \webvimark\modules\UserManagement\models\search\UserSearch();

     
    $params = Yii::$app->request->queryParams;
    if (isset($params['UserSearch']['status']) && $params['UserSearch']['status'] === '') {
        unset($params['UserSearch']['status']);
    }
    $dataProvider = $searchModel->search($params);

   
    $assignmentTable = Yii::$app->getModule('user-management')->auth_assignment_table; 
 

    $dataProvider->query
    ->join('LEFT JOIN', $assignmentTable . ' a', 'a.user_id = u.id')
    ->andWhere(['a.item_name' => 'sales-person'])
    ->andWhere(['u.status' => 1]);
        
    return $this->render('sales-persons', [
        'dataProvider' => $dataProvider,
        'searchModel'  => $searchModel,
    ]);
}

    


    public function actionSalesPersonView($id)
    {
        $salesPerson = User::findOne($id);
        if (!$salesPerson) {
            throw new NotFoundHttpException('Salesperson not found.');
        }

        $assignedUsers = User::find()
            ->where(['sales_person' => $id])
            ->all();

        return $this->render('sales-person-view', [
            'salesPerson'  => $salesPerson,
            'assignedUsers'=> $assignedUsers,
        ]);
    }

    

    public function actionAddStudent()
    {
        $model   = new \backend\models\Student();
        $parents = \webvimark\modules\UserManagement\models\User::find()
            ->select(['username', 'id'])
            ->indexBy('id')
            ->column();

        if ($model->load(Yii::$app->request->post())) {
            $file = \yii\web\UploadedFile::getInstance($model, 'details');

            if ($file) {
                $fileName = Yii::$app->security->generateRandomString() . '.' . $file->extension;
                $filePath = Yii::getAlias('@frontend/web/uploads/') . $fileName;
                if ($file->saveAs($filePath)) {
                    $model->details = $fileName;
                }
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Student added successfully.');
                return $this->redirect(['index']);
            }
        }

        return $this->render('add-student', [
            'model'   => $model,
            'parents' => $parents,
        ]);
    }

    /**
     * Change password for a user
     * @param int $id
     */
    public function actionChangePassword($id)
    {
        $model = User::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('User not found');
        }

        $model->scenario = 'changePassword';
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id'=>$model->id]);
        }

        return $this->renderIsAjax('changePassword', compact('model'));
    }

 
    // add students on user view page    
    public function actionAddStudentInline($id)
    {
        $user = User::findOne($id);
        if (!$user) {
            throw new \yii\web\NotFoundHttpException('User not found.');
        }

        $student = new \backend\models\Student([
            'parent_id' => $user->id,
            'status'    => 1,
        ]);

        if ($student->load(Yii::$app->request->post()) && $student->save()) {
            Yii::$app->session->setFlash('success', 'Student added.');
            return $this->redirect(['view', 'id' => $user->id]);
        }

        // validation error
        Yii::$app->session->setFlash('error', 'Unable to add student.');
        return $this->redirect(['view', 'id' => $user->id]);
    }

    // edit students on user view page   
    public function actionEditStudentInline($id)
    {
        $user = User::findOne($id);
        if (!$user) { throw new \yii\web\NotFoundHttpException('User not found.'); }

        $post = Yii::$app->request->post('Student', []);
        $sid  = $post['id'] ?? null;
        if (!$sid) {
            Yii::$app->session->setFlash('error', 'Invalid student.');
            return $this->redirect(['view','id'=>$id]);
        }

        $student = \backend\models\Student::findOne(['id'=>$sid, 'parent_id'=>$id]);
        if (!$student) {
            Yii::$app->session->setFlash('error', 'Student not found.');
            return $this->redirect(['view','id'=>$id]);
        }

        if ($student->load(Yii::$app->request->post()) && $student->save()) {
            Yii::$app->session->setFlash('success', 'Student updated.');
        } else {
            Yii::$app->session->setFlash('error', 'Unable to update student.');
        }
        return $this->redirect(['view','id'=>$id]);
    }

    public function actionDeleteStudentInline($id, $sid)
    {
        $user = User::findOne($id);
        if (!$user) { throw new \yii\web\NotFoundHttpException('User not found.'); }

        $student = \backend\models\Student::findOne(['id'=>$sid, 'parent_id'=>$id]);
        if (!$student) {
            Yii::$app->session->setFlash('error', 'Student not found.');
            return $this->redirect(['view','id'=>$id]);
        }

        // Soft delete
        $student->status = 0;
        if ($student->save(false, ['status'])) {
            Yii::$app->session->setFlash('success', 'Student deleted.');
        } else {
            Yii::$app->session->setFlash('error', 'Delete failed.');
        }
        return $this->redirect(['view','id'=>$id]);
    }






public function actionParentSearch($q = null, $term = null)
{
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    // Select2 sometimes sends "term" instead of "q"
    $needle = trim((string)($q ?? $term ?? ''));

    if ($needle === '') {
        return ['results' => []];
    }

    $rows = \webvimark\modules\UserManagement\models\User::find()
        ->alias('u')
        ->select([
            'u.id',
            new \yii\db\Expression(
                "CONCAT(u.username, ' (', COALESCE(u.email, 'no-email'), ')') AS text"
            ),
        ])
        // ⚠️ remove role filter so all users are searchable:
        // ->joinWith(['authAssignments aa'], false)
        // ->andWhere(['aa.item_name' => 'parent'])
        ->andFilterWhere(['like', 'u.username', $needle])
        ->orFilterWhere(['like', 'u.email', $needle])
        ->orderBy(['u.username' => SORT_ASC])
        ->limit(20)
        ->asArray()
        ->all();

    return ['results' => $rows];
}




}
