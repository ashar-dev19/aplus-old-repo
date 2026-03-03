<?php



namespace frontend\controllers;



use Yii;

use frontend\models\Subject;
use frontend\models\Grade;
use frontend\models\Chapter;
use frontend\models\LessonRead;
use frontend\models\search\GradeSearch;
use frontend\models\search\SubjectSearch;
use frontend\models\Student;
use yii\web\Controller;

use yii\web\NotFoundHttpException;

use yii\filters\VerbFilter;



/**

 * GradeController implements the CRUD actions for Grade model.

 */

class GradeController extends Controller

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

     * Lists all Grade models.

     * @return mixed

     */

    public function actionIndex()

    {
        $searchModel = new GradeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $this->layout = '@frontend/views/layouts/_minimal.php';

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }

    
    // public function actionGrades()
    // {
    //     $searchModel = new GradeSearch();
    //     $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    
    //     $this->layout = '@frontend/views/layouts/_minimal.php';   
    
    //     return $this->render('index', [
    //         'searchModel' => $searchModel,
    //         'dataProvider' => $dataProvider,
    //     ]);
    // }
    
    public function actionGrades($id, $subjectid)
    {
        // Fetch all grades
        $allGrades = Grade::find()->orderBy('title ASC')->all();
        
        // Filter grades based on the subject using custom logic
        $filteredGrades = [];
        foreach ($allGrades as $grade) {
            if ($this->checkGradeForSubject($grade->id, $subjectid)) {
                // Fetch chapters for each grade
                $chapters = Chapter::find()->where(['grade_id' => $grade->id, 'subject_id' => $subjectid])->all();
                $filteredGrades[] = [
                    'grade' => $grade,
                    'chapters' => $chapters,
                ];
            }
        }
        
        // Fetch the subject
        $subject = Subject::findOne($subjectid); 
        
        // Fetch the student data
        $student = Student::findOne($id);
        
        $this->layout = '@frontend/views/layouts/_minimal.php';
        return $this->render('index', [
            'gradesWithChapters' => $filteredGrades,
            'subject' => $subject,
            'student' => $student,
            'id' => $id,
        ]);
    }
    
    
    private function checkGradeForSubject($gradeId, $subjectId)
    {
        
        $chapters = Chapter::findAll(['grade_id' => $gradeId, 'subject_id' => $subjectId]);
        return !empty($chapters);
    }

    
     public function actionTopic()
    {
        $this->layout = '@frontend/views/layouts/_minimal.php';
        return $this->render('topic');

    }
   
    /**

     * Displays a single Grade model.

     * @param integer $id

     * @return mixed

     */

    public function actionView($id)

    {
         return $this->render('view', [
            'model' => $this->findModel($id),
        ]);

    }

    /**

     * Creates a new Grade model.

     * If creation is successful, the browser will be redirected to the 'view' page.

     * @return mixed

     */

    public function actionCreate()

    {
        $model = new Grade();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);

    }

    /**
     * Updates an existing Grade model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
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

     * Deletes an existing Grade model.

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

     * Finds the Grade model based on its primary key value.

     * If the model is not found, a 404 HTTP exception will be thrown.

     * @param integer $id

     * @return Grade the loaded model

     * @throws NotFoundHttpException if the model cannot be found

     */

    protected function findModel($id)

    {

        if (($model = Grade::findOne($id)) !== null) {

            return $model;

        }

        throw new NotFoundHttpException('The requested page does not exist.');

    }

}

