<?php
namespace frontend\controllers;

use Yii;
use frontend\models\LessonContent;
use frontend\models\search\LessonContentSearch;
use frontend\models\TopicIndexQuestionOptions; // Add this line
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
/**
 * LessonContentController implements the CRUD actions for LessonContent model.
 */
class LessonContentController extends Controller
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
     * Lists all LessonContent models.
     * @return mixed
     */
    public function actionIndex()
    {
        // $searchModel = new LessonContentSearch();
        // $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $topicsSearch = new LessonContentSearch();
        $queryParams["LessonContentSearch"]["lesson_id"] = Yii::$app->request->get('id') ;
        $dataProvider = $searchModel->search($queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


   /**
     * Displays a single LessonContent model.
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
    

      
    public function actionTutorial($lesson_id, $id = null)
    {
        $lessonContent = LessonContent::find()
            ->where(['lesson_id' => $lesson_id])
            ->orderBy('id')
            ->all();

        $this->layout = '@frontend/views/layouts/_noheader.php';

        // Pass lesson_id to the view
        return $this->render('tutorial', [
            'lessonContent' => $lessonContent,
            'lesson_id' => $lesson_id,
        ]);
    }



    public function actionTest($lesson_id)
    {
        $lessonContent = LessonContent::find()
            ->where(['lesson_id' => $lesson_id])
            ->orderBy('id')
            ->all();
    
        if ($lessonContent !== null) {
            $this->layout = '@frontend/views/layouts/_noheader.php';
    
            return $this->render('test', [
                'lessonContent' => $lessonContent,
                'lesson_id' => $lesson_id,
            ]);
        } else {
            throw new NotFoundHttpException('Lesson content not found for lesson ID: ' . $lesson_id);
        }
    }

       

    public function actionSaveQuiz()
    {
        $lesson_id = Yii::$app->request->post('lesson_id');
        $answers = Yii::$app->request->post('answers');
    
        // Initialize variables to calculate points
        $totalQuestions = count($answers);
        $correctAnswers = 0;
        
        // Assuming LessonTest model exists
        $lessonTest = new LessonTest();
        $lessonTest->lesson_id = $lesson_id;
        // Set the student_id, attempt, date_started, and any other required fields
    
        foreach ($answers as $question_id => $selectedAnswer) {
            // Assuming LessonContent model has relation to questions
            $question = LessonContent::findOne($question_id);

            $options = TopicIndexQuestionOptions::find()
            ->where(['question_id' => $question->question_id])
            ->orderBy('id')
            ->all();
    
            // Check if the selected answer is correct
            // For example, you might have correct_answer field in your LessonContent model
            if ($question->correct_answer === $selectedAnswer) {
                $correctAnswers++;
            }

            
            
            // Save the user's answers to the database (You need to define your own logic here)
            // For example, you might have a separate table to store user's answers
    
            // Example:
            /*
            $userAnswer = new UserAnswer();
            $userAnswer->lesson_test_id = $lessonTest->id; // Assuming you have lesson_test_id in your UserAnswer table
            $userAnswer->lesson_content_id = $question_id;
            $userAnswer->selected_answer = $selectedAnswer;
            $userAnswer->save();
            */
        }
    
        // Calculate points
        // For example, you might give 1 point for each correct answer
        $points = $correctAnswers;
        $lessonTest->points = $points;
        $lessonTest->total_points = $totalQuestions;
        // Set other fields like date_completed, status, etc.
    
        // Save the lesson test
        $lessonTest->save();
    
        // After saving, you can redirect the user or do any further processing
        return $this->redirect(['result-page']);
    }





    /**
     * Creates a new LessonContent model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new LessonContent();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing LessonContent model.
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
     * Deletes an existing LessonContent model.
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
     * Finds the LessonContent model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LessonContent the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LessonContent::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
