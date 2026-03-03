<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Chapter;
use frontend\models\search\ChapterSearch;
use frontend\models\Lesson;
use frontend\models\LessonTestAttempt;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\db\Query;
use yii\db\Expression;

/**
 * ChapterController implements the CRUD actions for Chapter model.
 */
class ChapterController extends Controller
{
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
     * Lists all Chapter models.
     * @return mixed
     */
    

    //  public function actionIndex()
    //  {
    //      $searchModel = new ChapterSearch();
     
    //      // Get subject_id and grade_id from request
    //      $subjectId = Yii::$app->request->get('subject_id');
    //      $gradeId = Yii::$app->request->get('grade_id');

    //      // Store subject_id and grade_id in session
    //     Yii::$app->session->set('subjectId', $subjectId);
    //     Yii::$app->session->set('gradeId', $gradeId);
        

    //       // Retrieve current student ID from session
    //     $currentStudent = Yii::$app->session->get('current_student');
    //     $studentId = $currentStudent ? $currentStudent['id'] : null;

    
    //      // Pass the parameters to the search method
    //      $queryParams = [
    //          "ChapterSearch" => [
    //              "subject_id" => $subjectId,
    //              "grade_id" => $gradeId
    //          ]
    //      ];
        
    //      // Configure the DataProvider without pagination
    //      $dataProvider = $searchModel->search($queryParams);
    //      $dataProvider->pagination = false; // Disable pagination to show all records


    //             // Calculate progress for each chapter
    //         foreach ($dataProvider->models as $chapter) {
    //             // Get the total number of lessons in this chapter
    //             $totalLessons = Lesson::find()->where(['chapter_id' => $chapter->id])->count();

    //             // Get the number of unique lessons the student has taken tests for in this chapter
    //             $completedLessons = LessonTestAttempt::find()
    //                 ->innerJoin('lesson', 'lesson_test_attempt.lesson_test_id = lesson.id')
    //                 ->where([
    //                     'lesson.chapter_id' => $chapter->id,
    //                     'lesson_test_attempt.student_id' => $studentId,
    //                 ])
    //                 ->select('lesson_test_attempt.lesson_test_id')
    //                 ->distinct() // Count each lesson only once
    //                 ->count();

    //             // Calculate the progress percentage
    //             $progress = ($totalLessons > 0) ? ($completedLessons / $totalLessons) * 100 : 0;

    //             // Ensure progress does not exceed 100%
    //             $chapter->progress = min($progress, 100);
    //         }

    //         $grade = \frontend\models\Grade::findOne($gradeId);
    //         $gradeName = $grade ? $grade->title : 'Grade';

    //         // Remove the prefix (alphabetic part and dash)
           
    //         // $gradeName = preg_replace('/^[A-Za-z\-]+/', '', $gradeName);
    //         $gradeName = preg_replace('/^[A-Za-z]-/', '', $gradeName);


     
    //      $this->layout = '@frontend/views/layouts/_minimal.php';
     
    //      return $this->render('index', [
    //          'searchModel' => $searchModel,
    //          'dataProvider' => $dataProvider,
    //          'subjectId' => $subjectId,  
    //          'gradeId' => $gradeId,  
    //          'studentId' => $studentId, 
    //          'gradeName' => $gradeName, 
              
    //      ]);
    //  }
     
    public function actionIndex()
{
    $searchModel = new ChapterSearch();

    $subjectId = (int)Yii::$app->request->get('subject_id');
    $gradeId   = (int)Yii::$app->request->get('grade_id');

    Yii::$app->session->set('subjectId', $subjectId);
    Yii::$app->session->set('gradeId', $gradeId);

    $studentId = Yii::$app->session->get('current_student')['id'] ?? null;

    $queryParams = [
        'ChapterSearch' => [
            'subject_id' => $subjectId,
            'grade_id'   => $gradeId,
        ],
    ];

    $dataProvider = $searchModel->search($queryParams);
    $dataProvider->pagination = false;

    $dataProvider->query->select(['id','title','subject_id','grade_id']);

    $chaptersProgress = [];
    $chapterIds = array_map(static fn($c) => $c->id, $dataProvider->getModels());

    if ($chapterIds) { 
        $cacheKey = ['chap_prog', 'sid'=>$studentId, 'sub'=>$subjectId, 'gr'=>$gradeId, 'ids'=>implode(',',$chapterIds)];

        $chaptersProgress = Yii::$app->cache->getOrSet($cacheKey, function () use ($chapterIds, $studentId) {
            $db = Yii::$app->db;

             
            // $totals = (new \yii\db\Query())
            //     ->select(['chapter_id', 'total' => 'COUNT(*)'])
            //     ->from('lesson')
            //     ->where(['chapter_id' => $chapterIds])
            //     ->groupBy('chapter_id')
            //     ->indexBy('chapter_id')
            //     ->column($db);  

             
            // $completed = (new \yii\db\Query())
            //     ->select(['l.chapter_id', 'completed' => 'COUNT(DISTINCT lta.lesson_test_id)'])
            //     ->from(['l' => 'lesson'])
            //     ->innerJoin(['lta' => 'lesson_test_attempt'], 'lta.lesson_test_id = l.id AND lta.student_id = :sid', [':sid' => $studentId])
            //     ->where(['l.chapter_id' => $chapterIds])
            //     ->groupBy('l.chapter_id')
            //     ->indexBy('chapter_id')
            //     ->column($db);

          // totals per chapter  [chapter_id => total_lessons]
            $totals = (new Query())
                ->select([
                    'total'      => new Expression('COUNT(*)'), // <-- pehla column value banega
                    'chapter_id',
                ])
                ->from('lesson')
                ->where(['chapter_id' => $chapterIds])
                ->groupBy('chapter_id')
                ->indexBy('chapter_id') 
                ->column(); 

            
            $completed = (new Query())
                ->select([
                    'completed'  => new Expression('COUNT(DISTINCT lta.lesson_test_id)'), // pehla column
                    'chapter_id' => 'l.chapter_id',                                       // key banane ke liye alias
                ])
                ->from(['l' => 'lesson'])
                ->leftJoin(['lta' => 'lesson_test_attempt'],
                    'lta.lesson_test_id = l.id AND lta.student_id = :sid',
                    [':sid' => $studentId]
                )
                ->where(['l.chapter_id' => $chapterIds])
                ->groupBy('l.chapter_id')
                ->indexBy('chapter_id')
                ->column();


            $out = [];
            foreach ($chapterIds as $cid) {
                $t = (int)($totals[$cid] ?? 0);
                $c = (int)($completed[$cid] ?? 0);
                $out[$cid] = $t > 0 ? (int)round(min(100, ($c / $t) * 100)) : 0;
            }
            return $out;
        }, 300);  
    }

    $grade = \frontend\models\Grade::findOne($gradeId);
    $gradeName = $grade ? $grade->title : 'Grade';


    

    $this->layout = '@frontend/views/layouts/_minimal.php';

    return $this->render('index', [
        'searchModel'       => $searchModel,
        'dataProvider'      => $dataProvider,
        'subjectId'         => $subjectId,
        'gradeId'           => $gradeId,
        'studentId'         => $studentId,
        'gradeName'         => $gradeName,
        'chaptersProgress'  => $chaptersProgress,
    ]);
}


     


    /**
     * Displays a single Chapter model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Chapter model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Chapter();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Chapter model.
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

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Chapter model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Chapter model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Chapter the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Chapter::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
