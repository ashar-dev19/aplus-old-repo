<?php

namespace backend\controllers;

use Yii;
use backend\models\Student;
use backend\models\search\StudentSearch;
use frontend\models\LessonTestAttempt;
use backend\models\Notes;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\models\Subject;


 use yii\db\Expression;
use yii\db\Query;
 use frontend\models\Points;
 use yii\web\UploadedFile;


/**
 * StudentController implements the CRUD actions for Student model.
 */
class StudentController extends Controller
{

    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Student models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StudentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    // public function actionEditPoints($id)
    // {
    //     $student = Student::findOne($id);
    
    //     if (!$student) {
    //         throw new NotFoundHttpException('Student not found.');
    //     }
    
    //     if (Yii::$app->request->isPost) {
    //         $points = Yii::$app->request->post('points');
    //         LessonTestAttempt::updateAll(['score' => $points], ['student_id' => $id]);
    //         Yii::$app->session->setFlash('success', 'Points updated successfully.');
    //         return $this->redirect(['index']);
    //     }
    
    //     return $this->render('edit-points', [
    //         'student' => $student,
    //     ]);
    // }
    
 
    public function actionAddScore($student_id)
{
    $student = Student::findOne($student_id);
    if (!$student) {
        Yii::$app->session->setFlash('error', 'Student not found.');
        return $this->redirect(['index']);
    }

    // --- TOTAL (earn − redeem) ---
    $sumExpr = new \yii\db\Expression("SUM(CASE WHEN is_redempt = 1 THEN -points ELSE points END)");
    $totalPoints = (new \yii\db\Query())
        ->from(\frontend\models\Points::tableName())
        ->select($sumExpr)
        ->where(['student_id' => $student_id, 'status' => 1])
        ->scalar();
    $totalPoints = (int)($totalPoints ?? 0);

    if (Yii::$app->request->isPost) {
        $add   = (int)Yii::$app->request->post('add_points', 0);     // positive add
        $deduct= (int)Yii::$app->request->post('deduct_points', 0);  // positive number (we will treat as deduction)
        $details = trim((string)Yii::$app->request->post('details', ''));

        // ADD (earn row) -> is_redempt = NULL
        if ($add > 0) {
            $p = new \frontend\models\Points();
            $p->student_id  = $student_id;
            $p->points      = $add;
            $p->status      = 1;
            $p->is_redempt  = null;
            $p->details     = $details !== '' ? $details : 'Manual add';
            $p->created_by  = Yii::$app->user->id ?? null;
            $p->updated_by  = Yii::$app->user->id ?? null;
            $p->save(false);
            Yii::$app->session->setFlash('success', "+{$add} points added.");
        }

        // DEDUCT (redeem row) -> is_redempt = 1
        if ($deduct > 0) {
            $deduct = min($deduct, $totalPoints); // prevent going negative
            if ($deduct > 0) {
                $p = new \frontend\models\Points();
                $p->student_id  = $student_id;
                $p->points      = $deduct;     // store as positive
                $p->status      = 1;
                $p->is_redempt  = 1;           // means “minus” in total
                $p->details     = $details !== '' ? $details : 'Manual deduction';
                $p->created_by  = Yii::$app->user->id ?? null;
                $p->updated_by  = Yii::$app->user->id ?? null;
                $p->save(false);
                Yii::$app->session->setFlash('success', "−{$deduct} points deducted.");
            }
        }

        return $this->refresh();
    }

    return $this->render('add-score', [
        'student'     => $student,
        'pointsRow'   => null,          // ab single-row ki zaroorat nahi
        'totalPoints' => $totalPoints,  // CASE SUM se aaya hua total
    ]);
}



    
    

    
    
    



    /**
     * Displays a single Student model.
     * @param integer $id
     * @return mixed
     */
    // public function actionView($id)
    // {
    //     // Fetch the student record
    //     $model = $this->findModel($id);
    
    //     // Get date filter from the request
    //     $dateFilter = Yii::$app->request->get('date', null);
    
    //     // Fetch test attempts for the student, optionally filter by date
    //     $query = LessonTestAttempt::find()->where(['student_id' => $id]);
    //     if ($dateFilter) {
    //         $query->andWhere(['DATE(created_at)' => $dateFilter]);
    //     }
    
    //     $testAttempts = $query->all();
    
    //     // Pass the data to the view
    //     return $this->render('view', [
    //         'model' => $model,
    //         'testAttempts' => $testAttempts,
    //     ]);
    // }
    
//     public function actionView($id)
// {
//     $date  = Yii::$app->request->get('date');

//     $query = \frontend\models\LessonTestAttempt::find()
//         ->alias('a')
//         ->where(['a.student_id' => $id])
//         ->orderBy(['a.created_at' => SORT_DESC])
//         ->with(['lesson.subject', 'lesson.chapter']); // eager load

//     // Agar created_at UNIX int hai:
//     if ($date) {
//         $from = strtotime($date . ' 00:00:00');
//         $to   = strtotime($date . ' 23:59:59');
//         $query->andWhere(['between', 'a.created_at', $from, $to]);
//     }

//     $testAttempts = $query->all();

//     $notesModel   = new \backend\models\Notes();
//     if ($notesModel->load(Yii::$app->request->post()) && $notesModel->save()) {
//         Yii::$app->session->setFlash('success', 'Note added successfully!');
//         return $this->refresh();
//     }

//     $studentModel = $this->findModel($id);

//     return $this->render('view', [
//         'model'        => $studentModel,
//         'testAttempts' => $testAttempts,
//         'studentModel' => $studentModel,
//         'notesModel'   => $notesModel,
//     ]);
// }


public function actionView($id)
{
    $studentModel = $this->findModel($id);

    // ---- Date range (frontend jaisa: "YYYY-mm-dd to YYYY-mm-dd")
    $selectedDateRange = Yii::$app->request->get('date');
    $startDate = $endDate = null;

    // First visit -> current month, warna jo user de
    $isFirstVisit = empty(Yii::$app->request->queryParams);
    if ($isFirstVisit) {
        $firstDay = date('Y-m-01');
        $lastDay  = date('Y-m-t');
        $startDate = $firstDay . ' 00:00:00';
        $endDate   = $lastDay  . ' 23:59:59';
        $selectedDateRange = $firstDay . ' to ' . $lastDay;
    } else if ($selectedDateRange && trim($selectedDateRange) !== '') {
        if (strpos($selectedDateRange, ' to ') !== false) {
            [$s,$e] = explode(' to ', $selectedDateRange);
            $startDate = date('Y-m-d 00:00:00', strtotime($s));
            $endDate   = date('Y-m-d 23:59:59', strtotime($e));
        } else {
            $startDate = date('Y-m-d 00:00:00', strtotime($selectedDateRange));
            $endDate   = date('Y-m-d 23:59:59', strtotime($selectedDateRange));
        }
    }

    // ---- Base attempts
    $q = \frontend\models\LessonTestAttempt::find()
        ->alias('a')
        ->with(['lesson.subject','lesson.chapter']) // eager-load
        ->where(['a.student_id' => $id])
        ->orderBy(['a.created_at' => SORT_DESC]);

    if ($startDate && $endDate) {
        // created_at INT / DATETIME dono handle:
        $col = 'a.created_at';
        $schema = Yii::$app->db->schema->getTableSchema('lesson_test_attempt', true);
        $isInt = $schema && isset($schema->columns['created_at']) && $schema->columns['created_at']->type === 'integer';
        if ($isInt) {
            $q->andWhere(['between', $col, strtotime($startDate), strtotime($endDate)]);
        } else {
            $q->andWhere(['between', 'FROM_UNIXTIME(a.created_at)', $startDate, $endDate]);
        }
    }

    $attempts = $q->all();

    // ---- Preload total points per lesson
    $lessonIds = array_values(array_unique(array_map(fn($a)=> (int)$a->lesson_test_id, $attempts)));
    $totalPointsMap = [];
    if ($lessonIds) {
        $rows = \frontend\models\LessonContent::find()
            ->select(['lesson_id', 'SUM(points) AS total'])
            ->where(['lesson_id' => $lessonIds])
            ->groupBy('lesson_id')->asArray()->all();
        foreach ($rows as $r) $totalPointsMap[(int)$r['lesson_id']] = (int)$r['total'];
    }

    // ---- Group by lesson: latest attempt + attempts count
    $grouped = [];
    foreach ($attempts as $a) {
        if (!$a->lesson) continue;
        $lid = (int)$a->lesson->id;

        if (!isset($grouped[$lid])) {
            $grouped[$lid] = [
                'lesson'         => $a->lesson,
                'attempts'       => 0,
                'latest_attempt' => $a,  // because we ordered DESC
                'total_points'   => $totalPointsMap[$lid] ?? 0,
            ];
        }
        $grouped[$lid]['attempts']++;
        // latest already first, but keep safe:
        if ($a->created_at > $grouped[$lid]['latest_attempt']->created_at) {
            $grouped[$lid]['latest_attempt'] = $a;
        }
    }

    // Array for the view
    $testAttempts = array_values($grouped);

    // Notes create (same as before)
    $notesModel = new \backend\models\Notes();
    if ($notesModel->load(Yii::$app->request->post()) && $notesModel->save()) {
        Yii::$app->session->setFlash('success', 'Note added successfully!');
        return $this->refresh();
    }

    return $this->render('view', [
        'model'              => $studentModel,
        'testAttempts'       => $testAttempts,
        'notesModel'         => $notesModel,
        'selectedDateRange'  => $selectedDateRange,
    ]);
}





    public function actionCreateNote()
    {
        $model = new Notes();
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Note added successfully!');
            return $this->redirect(['student/view', 'id' => $model->student_id]);
        }
        
        return $this->render('create-note', ['model' => $model]);
    }

    

  


    /**
     * Creates a new Student model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
   public function actionCreate()
{
    $model = new Student();

    if ($model->load(Yii::$app->request->post())) {
        $file = UploadedFile::getInstance($model, 'details');

        if ($file) {
            $fileName = uniqid('stu_') . '.' . $file->extension;
            $absPath  = Yii::getAlias('@frontend/web/uploads/') . $fileName;

            if ($file->saveAs($absPath)) {
                // store relative path so it works on web: /uploads/xxx.jpg
                $model->details = 'uploads/' . $fileName;
            } else {
                Yii::$app->session->setFlash('error', 'Image upload failed.');
            }
        }

        if ($model->save(false)) {
            Yii::$app->session->setFlash('success', 'Student created.');
            return $this->redirect(['view', 'id' => $model->id]);
        }
    }

    return $this->render('create', ['model' => $model]);
}

public function actionUpdate($id)
{
    $model = $this->findModel($id);
    $oldImage = $model->details; // keep old if no new upload

    if ($model->load(Yii::$app->request->post())) {
        $file = UploadedFile::getInstance($model, 'details');

        if ($file) {
            $fileName = uniqid('stu_') . '.' . $file->extension;
            $absPath  = Yii::getAlias('@frontend/web/uploads/') . $fileName;

            if ($file->saveAs($absPath)) {
                // optionally delete old file (skip defaults)
                if ($oldImage && $oldImage !== 'uploads/user.png') {
                    $oldAbs = Yii::getAlias('@frontend/web/') . ltrim($oldImage, '/');
                    if (is_file($oldAbs)) @unlink($oldAbs);
                }
                $model->details = 'uploads/' . $fileName;
            } else {
                Yii::$app->session->setFlash('error', 'Image upload failed.');
                $model->details = $oldImage;
            }
        } else {
            $model->details = $oldImage; // no new file
        }

        if ($model->save(false)) {
            Yii::$app->session->setFlash('success', 'Student updated.');
            return $this->redirect(['view', 'id' => $model->id]);
        }
    }

    return $this->render('update', ['model' => $model]);
}

    /**
     * Deletes an existing Student model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Student model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Student the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */

     


    protected function findModel($id)
    {
        if (($model = Student::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
