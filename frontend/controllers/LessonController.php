<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Chapter;
use frontend\models\Grade;
use frontend\models\Lesson;
use frontend\models\search\LessonSearch;
use frontend\models\LessonTestAttempt;
use frontend\models\LessonContent;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * LessonController implements the CRUD actions for Lesson model.
 */
class LessonController extends Controller
{
    // public $freeAccess= true;
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Lesson models.
     * @return mixed
     */
    // public function actionIndex()
    // {
    //     $searchModel = new LessonSearch();
    //     $queryParams["LessonSearch"]["chapter_id"] = Yii::$app->request->get('id');
        
    //     $chapter = Chapter::findOne($queryParams["LessonSearch"]["chapter_id"]);
    //     if ($chapter !== null) {
    //         Yii::$app->session->set('chapterName', $chapter->title);
    //     }
    //     // Retrieve chapter name from session
    //     $chapterName = Yii::$app->session->get('chapterName', 'Unknown Chapter'); 


    //     // Retrieve grade_id, subject_id and student details from the session
    //     $gradeId = Yii::$app->session->get('gradeId');
    //     $subjectId = Yii::$app->session->get('subjectId');
    //     $selectedStudent = Yii::$app->session->get('current_student');

    //      // Fetch grade name based on gradeId
    //     $grade = Grade::findOne($gradeId);
    //     $gradeName = $grade ? $grade->title : 'Unknown Grade';

    
    //     $dataProvider = $searchModel->search($queryParams);
    
    //     // Clear previous lesson name from the session
    //     Yii::$app->session->remove('lessonName');
    
    //     // Retrieve the lesson name based on chapter_id
    //     $lesson = Lesson::findOne(['chapter_id' => $queryParams["LessonSearch"]["chapter_id"]]);
    
    //     if ($lesson !== null) {
    //         // Store the lesson name in the session
    //         Yii::$app->session->set('lessonName', $lesson->title);
    //     } else {
    //         // Optionally handle the case where the lesson is not found
    //         Yii::$app->session->set('lessonName', 'Lesson not found');
    //     }
    
    //     // Prepare data with percentages for each lesson
    //     $lessonModels = [];
    //     foreach ($dataProvider->models as $model) {
    //         // Get the total points for the lesson
    //         $totalPoints = LessonContent::find()->where(['lesson_id' => $model->id])->sum('points');
    
    //         // Get the latest attempt for the student
    //         $latestAttempt = LessonTestAttempt::find()
    //             ->where(['lesson_test_id' => $model->id, 'student_id' => $selectedStudent])
    //             ->orderBy(['created_at' => SORT_DESC])
    //             ->one();
    
    //         // Calculate percentage
    //         $percentage = "N/A"; // Default to N/A
    //         if ($latestAttempt) {
    //             $score = $latestAttempt->score; // Assuming score is stored as a raw score
    //             $percentage = ($totalPoints > 0) ? ($score / $totalPoints) * 100 : 0; // Calculate percentage
    //             $percentage = min($percentage, 100); // Ensure percentage does not exceed 100
    //             $percentage = round($percentage, 2); // Round percentage to 2 decimal places
    //         }
    
    //         // Add model and percentage to array
    //         $lessonModels[] = [
    //             'model' => $model,
    //             'percentage' => $percentage, // Use "N/A" or percentage
    //         ];
    //     }

    //     $lessonName = Yii::$app->session->get('lessonName', 'Lesson not found');
    
    //     $this->layout = '@frontend/views/layouts/_minimal.php';
    //     return $this->render('index', [
    //         'searchModel' => $searchModel,
    //         'dataProvider' => $dataProvider,
    //         'gradeId' => $gradeId,
    //         'subjectId' => $subjectId,
    //         'lessonModels' => $lessonModels, // Pass lesson models with percentages
    //         'selectedStudent' => $selectedStudent,
    //         'gradeName' => $gradeName, 
    //         'chapterName' => $chapterName,
    //         'lessonName' => $lessonName, 
    //     ]);
    // }
    

//     public function actionIndex()
// {
//     $searchModel = new LessonSearch();
//     $queryParams["LessonSearch"]["chapter_id"] = Yii::$app->request->get('id');

   
//     $chapter = Chapter::findOne($queryParams["LessonSearch"]["chapter_id"]);
//     if ($chapter !== null) {
//         Yii::$app->session->set('chapterName', $chapter->title);
//     }
//     $chapterName = Yii::$app->session->get('chapterName', 'Unknown Chapter');

     
//     $gradeId        = Yii::$app->session->get('gradeId');
//     $subjectId      = Yii::$app->session->get('subjectId');

//     $selectedStudent = Yii::$app->session->get('current_student');  
    
//     $selectedStudentId = is_array($selectedStudent) ? ($selectedStudent['id'] ?? null) : $selectedStudent;

     
//     $grade = Grade::findOne($gradeId);
//     $gradeName = $grade ? $grade->title : 'Unknown Grade';

 
//     $dataProvider = $searchModel->search($queryParams);
//     $dataProvider->query
//         ->select(['id', 'title'])    
//         ->with([]);                 

 
//     Yii::$app->session->remove('lessonName');
//     $lesson = Lesson::findOne(['chapter_id' => $queryParams["LessonSearch"]["chapter_id"]]);
//     Yii::$app->session->set('lessonName', $lesson ? $lesson->title : 'Lesson not found');

//     // ---------------- OPTIMIZED BLOCK (Batch queries) ----------------
//     $lessonModels = [];
//     $models = $dataProvider->getModels();

//     if (!empty($models)) {
         
//         $lessonIds = array_map(static function($m){ return (int)$m->id; }, $models);

//         $totalPointsRows = LessonContent::find()
//             ->select(['lesson_id', 'SUM(points) AS total_points'])
//             ->where(['lesson_id' => $lessonIds])
//             ->groupBy('lesson_id')
//             ->asArray()
//             ->all();
//         $totalPointsMap = [];
//         foreach ($totalPointsRows as $r) {
//             $totalPointsMap[(int)$r['lesson_id']] = (int)$r['total_points'];
//         }

//         // 3) Is student ke liye in lessons par latest attempt — single query
//         $latestAttemptsMap = [];
//         if (!empty($selectedStudentId)) {
//             $attemptRows = LessonTestAttempt::find()
//                 ->select(['id','lesson_test_id','score','created_at'])
//                 ->where([
//                     'student_id'      => $selectedStudentId,
//                     'lesson_test_id'  => $lessonIds,
//                 ])
//                 ->orderBy(['lesson_test_id' => SORT_ASC, 'created_at' => SORT_DESC])
//                 ->asArray()
//                 ->all();

//             foreach ($attemptRows as $row) {
//                 $lid = (int)$row['lesson_test_id'];
//                 if (!isset($latestAttemptsMap[$lid])) {
//                     $latestAttemptsMap[$lid] = $row;
//                 }
//             }
//         }

//         foreach ($models as $m) {
//             $lid       = (int)$m->id;
//             $total     = $totalPointsMap[$lid] ?? 0;
//             $latest    = $latestAttemptsMap[$lid] ?? null;

//             $percentage = "N/A";
//             if ($latest) {
//                 $score = (int)$latest['score'];
//                 $percentage = $total > 0 ? round(min(($score / $total) * 100, 100), 2) : 0;
//             }

//             $lessonModels[] = [
//                 'model'      => $m,
//                 'percentage' => $percentage,
//             ];
//         }
//     }
//     // ---------------- /OPTIMIZED BLOCK ----------------

//     $lessonName = Yii::$app->session->get('lessonName', 'Lesson not found');

//     $this->layout = '@frontend/views/layouts/_minimal.php';
//     return $this->render('index', [
//         'searchModel'     => $searchModel,
//         'dataProvider'    => $dataProvider,
//         'gradeId'         => $gradeId,
//         'subjectId'       => $subjectId,
//         'lessonModels'    => $lessonModels,   // same structure as before
//         'selectedStudent' => $selectedStudent, // original variable ko as-is pass kiya
//         'gradeName'       => $gradeName,
//         'chapterName'     => $chapterName,
//         'lessonName'      => $lessonName,
//     ]);
// }


public function actionIndex()
    {
        $searchModel = new LessonSearch();

         
        $chapterId = (int) Yii::$app->request->get('id');
        $queryParams['LessonSearch']['chapter_id'] = $chapterId;

        $chapter = Chapter::findOne($chapterId);
        if ($chapter === null) {
            throw new NotFoundHttpException('Chapter not found.');
        }

       
        // Breadcrumb me sahi chapter name
    Yii::$app->session->set('chapterName', $chapter->title);
    $chapterName = Yii::$app->session->get('chapterName', 'Unknown Chapter');

    // ---- 2) Grade/Subject (ALWAYS overwrite from chapter) ----
    $gradeId   = (int) $chapter->grade_id;
    $subjectId = (int) $chapter->subject_id;
    Yii::$app->session->set('gradeId',   $gradeId);
    Yii::$app->session->set('subjectId', $subjectId);

    // Grade name (fresh)
    $grade     = Grade::findOne($gradeId);
    $gradeName = $grade ? $grade->title : 'Unknown Grade';

    
        $selectedStudent   = Yii::$app->session->get('current_student');  
        $selectedStudentId = is_array($selectedStudent) ? ($selectedStudent['id'] ?? null) : $selectedStudent;
 
        $grade     = Grade::findOne($gradeId);
        $gradeName = $grade ? $grade->title : 'Unknown Grade';

         
        $dataProvider = $searchModel->search($queryParams);

        $dataProvider->pagination = false;

        $dataProvider->query
            ->select(['id','title'])
            ->with([]);

       
        Yii::$app->session->remove('lessonName');
        $lesson = Lesson::findOne(['chapter_id' => $chapterId]);
        Yii::$app->session->set('lessonName', $lesson ? $lesson->title : 'Lesson not found');

        // ---------------- OPTIMIZED BLOCK (Batch queries) ----------------
        $lessonModels = [];
        $models = $dataProvider->getModels();

        if (!empty($models)) {
            $lessonIds = array_map(static function($m){ return (int)$m->id; }, $models);
 
            $totalPointsRows = LessonContent::find()
                ->select(['lesson_id', 'SUM(points) AS total_points'])
                ->where(['lesson_id' => $lessonIds])
                ->groupBy('lesson_id')
                ->asArray()
                ->all();
            $totalPointsMap = [];
            foreach ($totalPointsRows as $r) {
                $totalPointsMap[(int)$r['lesson_id']] = (int)$r['total_points'];
            }
 
            $latestAttemptsMap = [];
            if (!empty($selectedStudentId)) {
                $attemptRows = LessonTestAttempt::find()
                    ->select(['id','lesson_test_id','score','created_at'])
                    ->where([
                        'student_id'     => $selectedStudentId,
                        'lesson_test_id' => $lessonIds,
                    ])
                    ->orderBy(['lesson_test_id' => SORT_ASC, 'created_at' => SORT_DESC])
                    ->asArray()
                    ->all();

                foreach ($attemptRows as $row) {
                    $lid = (int)$row['lesson_test_id'];
                    if (!isset($latestAttemptsMap[$lid])) {
                        $latestAttemptsMap[$lid] = $row;  
                    }
                }
            }  

            foreach ($models as $m) {
                $lid    = (int)$m->id;
                $total  = $totalPointsMap[$lid] ?? 0;
                $latest = $latestAttemptsMap[$lid] ?? null;

                $percentage = "N/A";
                if ($latest) {
                    $score = (int)$latest['score'];
                    $percentage = $total > 0 ? round(min(($score / $total) * 100, 100), 2) : 0;
                }

                $lessonModels[] = [
                    'model'      => $m,
                    'percentage' => $percentage,
                ];
            }
        }
        // ---------------- /OPTIMIZED BLOCK ----------------

        $lessonName = Yii::$app->session->get('lessonName', 'Lesson not found');

        $this->layout = '@frontend/views/layouts/_minimal.php';
        return $this->render('index', [
            'searchModel'     => $searchModel,
            'dataProvider'    => $dataProvider,
            'gradeId'         => $gradeId,      
            'subjectId'       => $subjectId,     
            'lessonModels'    => $lessonModels,
            'selectedStudent' => $selectedStudent,
            'gradeName'       => $gradeName,
            'chapterName'     => $chapterName,
            'lessonName'      => $lessonName,
        ]);
    }

    
    

    

    


    /**
     * Displays a single Lesson model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $this->layout = '@frontend/views/layouts/_minimal.php';
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Lesson model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Lesson();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $this->layout = '@frontend/views/layouts/_minimal.php';
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Lesson model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $this->layout = '@frontend/views/layouts/_minimal.php';
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Lesson model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        $this->layout = '@frontend/views/layouts/_minimal.php';
        return $this->redirect(['index']);
    }

    /**
     * Finds the Lesson model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Lesson the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Lesson::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
