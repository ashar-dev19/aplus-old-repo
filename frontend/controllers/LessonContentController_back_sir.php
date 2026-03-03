<?php

namespace frontend\controllers;

use Yii;
use frontend\models\LessonContent;
use frontend\models\TestQuestionAnswer;
use frontend\models\TopicIndexQuestionOptions;
use frontend\models\TopicIndexTestScore;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * LessonContentController implements the CRUD actions for LessonContent model.
 */
class LessonContentController extends Controller
{
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

    // Assuming TopicIndexTestScore model exists
    $testScore = new TopicIndexTestScore();
    $testScore->topic_index_id = $lesson_id; // Assuming this corresponds to lesson_id
    $testScore->member_id = Yii::$app->user->id; // Assuming you have a logged-in user
    $testScore->attempt = 1; // Assuming this is the first attempt, adjust as needed
    $testScore->date_started = date('Y-m-d H:i:s'); // Record the start time

    foreach ($answers as $question_id => $selectedAnswer) {
        // Assuming LessonContent model has relation to questions
        $question = LessonContent::findOne($question_id);

        // Fetch correct answer from TestQuestionAnswer model
        $correctAnswerModel = TestQuestionAnswer::findOne(['question_id' => $question_id]);
        if ($correctAnswerModel !== null) {
            $correctAnswer = $correctAnswerModel->answer;

            // Check if the selected answer is correct
            if ($correctAnswer === $selectedAnswer) {
                $correctAnswers++;
            }

            // Save the user's answers to the database if needed
            /*
            $userAnswer = new UserAnswer();
            $userAnswer->topic_index_test_score_id = $testScore->id;
            $userAnswer->lesson_content_id = $question_id;
            $userAnswer->selected_answer = $selectedAnswer;
            $userAnswer->save();
            */
        } else {
            Yii::warning("Correct answer not found for question ID: $question_id");
        }
    }

    // Calculate points based on correct answers
    $points = $correctAnswers;
    $testScore->score = $points;
    $testScore->total_score = $totalQuestions; // Total points available in the quiz
    $testScore->date_completed = date('Y-m-d H:i:s'); // Record the completion time
    $testScore->status = 'completed'; // Adjust status as needed

    if ($testScore->save()) {
        return $this->redirect(['result-page']); // Redirect to result page or any other page as needed
    } else {
        Yii::error('Failed to save TopicIndexTestScore: ' . print_r($testScore->errors, true));
        throw new \yii\web\ServerErrorHttpException('Failed to save quiz results. Please try again later.');
    }
}

public function actionCheckAnswer()
{
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    

    $question_id = Yii::$app->request->post('question_id');
    $selectedOptionId = Yii::$app->request->post('selectedAnswer');


    try {
        if (!$question_id || !$selectedOptionId) {
            throw new \Exception('Required parameters are missing.');
        }

        // Fetch the correct answer model from TestQuestionAnswer using the question_id
        $correctAnswerModel = TestQuestionAnswer::findOne(['question_id' => $question_id]);

        if ($correctAnswerModel === null) {
            throw new \Exception('Correct answer not found for this question.');
        }

        // Get the correct answer id from TopicIndexQuestionOptions
        $correctOption = TopicIndexQuestionOptions::findOne(['question_id' => $question_id, 'option_value' => $correctAnswerModel->answer]);

        if ($correctOption === null) {
            throw new \Exception('Correct option not found for this question.');
        }

        // Check if the selected answer id matches the correct answer id
        $is_correct = ($correctOption->option_value == $selectedOptionId);
      

        // Store is_correct in session or temporary variable if needed for future use
        Yii::$app->session->set('is_correct_' . $question_id, $is_correct);

        // Return appropriate response
        return [
            'status' => 'success',
            'is_correct' => $is_correct,
            'message' => $is_correct ? 'Correct answer!' : 'Wrong answer!',
        ];
    } catch (\Exception $e) {
        Yii::error("Failed to check answer: " . $e->getMessage(), __METHOD__);
        return ['status' => 'error', 'message' => $e->getMessage()];
    }
}



public function actionUpdatePoints()
{
    Yii::$app->response->format = Response::FORMAT_JSON;

    $question_id = Yii::$app->request->post('question_id');
    $selectedAnswer = Yii::$app->request->post('selectedAnswer');

    try {
        if (!$question_id || !$selectedAnswer) {
            throw new \Exception('Required parameters are missing.');
        }

        // Fetch correct answer from TestQuestionAnswer model
        $correctAnswerModel = TestQuestionAnswer::findOne(['question_id' => $question_id]);
        if ($correctAnswerModel === null) {
            throw new \Exception('Correct answer not found for question.');
        }
        $correctAnswer = $correctAnswerModel->answer;

        // Check if the selected answer is correct
        $is_correct = ($correctAnswer === $selectedAnswer);

        // Prepare styled HTML for correct answer (example, adjust as per your data structure)
        $correctAnswerHtml = $correctAnswer; // Assuming correctAnswer field contains HTML or image data

        // Log the selected answer and correctness
        Yii::info("Question ID: $question_id, Selected Answer: $selectedAnswer, Correct Answer: $correctAnswer, Is Correct: " . ($is_correct ? 'Yes' : 'No'));

        // Return success response
        return [
            'status' => 'success',
            'is_correct' => $is_correct,
            'correctAnswerHtml' => $correctAnswerHtml,
            'message' => $is_correct ? 'Correct answer!' : 'Wrong answer!',
        ];
    } catch (\Exception $e) {
        Yii::error("Failed to update points: " . $e->getMessage(), __METHOD__);
        return ['status' => 'error', 'message' => $e->getMessage()];
    }
}

    // Your existing CRUD actions like create, update, delete, etc. are unchanged

    protected function findModel($id)
    {
        if (($model = LessonContent::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

