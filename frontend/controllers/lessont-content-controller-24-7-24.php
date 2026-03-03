<?php

namespace frontend\controllers;

use Yii;
use frontend\models\LessonContent;
use frontend\models\LessonContentExplanation;
use frontend\models\TestQuestionAnswer;
use frontend\models\TopicIndexQuestionOptions;
use frontend\models\TopicIndexTestScore;
use frontend\models\LessonRead;
use frontend\models\Lesson;
use frontend\models\LessonTestAttempt;
use frontend\models\Student;
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
    
        // Retrieve student ID from session
        $currentStudent = Yii::$app->session->get('current_student');
        if (!$currentStudent) {
            throw new \Exception('Student details not found in session.');
        }
        $studentId = $currentStudent['id'];
    
        // Check if there's already an attempt for today
        $today = date('Y-m-d');
        $existingAttempt = LessonTestAttempt::find()
            ->where(['lesson_test_id' => $lesson_id, 'student_id' => $studentId])
            ->andWhere(['>=', 'created_at', strtotime($today . ' 00:00:00')])
            ->andWhere(['<=', 'created_at', strtotime($today . ' 23:59:59')])
            ->one();
    
        // Mark lesson as read
        $lessonRead = new LessonRead();
        $lessonRead->lesson_id = $lesson_id;
        $lessonRead->student_id = $studentId;
        $lessonRead->date = date('Y-m-d');
        $lessonRead->status = 1;
        $lessonRead->save();
    
        // Update the lesson name in the session
        $lesson = Lesson::findOne($lesson_id);
        if ($lesson !== null) {
            Yii::$app->session->set('lessonName', $lesson->title);
        } else {
            Yii::$app->session->set('lessonName', 'Lesson not found');
        }

        // Get the lesson name from the session
        $lessonName = Yii::$app->session->get('lessonName', 'Default Lesson Name'); // Default value if not found


        $this->layout = '@frontend/views/layouts/_noheader.php';
    
        // Pass lesson_id and existingAttempt to the view
        return $this->render('tutorial', [
            'lessonContent' => $lessonContent,
            'lesson_id' => $lesson_id,
            'existingAttempt' => $existingAttempt,
            'lessonName' => $lessonName, // Pass the lesson name to the view
        ]);
    }
    
    

     public function actionTest($lesson_id)
    {
        $lessonContent = LessonContent::find()
            ->where(['lesson_id' => $lesson_id])
            ->orderBy('id')
            ->all();

            // $testAttempt = new LessonTestAttempt(); 
            // $testAttempt->lesson_test_id = $lesson_id;
            // $testAttempt->student_id = 14;
            // $testAttempt->score = $lessonContent[0]->points;
            // // $testAttempt->date = date('Y-m-d');
            // $testAttempt->status= 1;
            // $testAttempt->save();

            // print_r($testAttempt->getErrors());
            // die();
       

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
    

    

public function actionCheckAnswer()
{
    
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $question_id = Yii::$app->request->post('question_id');
    $selectedOptionId = Yii::$app->request->post('selectedAnswer');
     
    $lessonContent = LessonContent::findOne(['question_id' => $question_id]);

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
            throw new \Exception('Correct option not found for this question.'.$correctAnswerModel->answer .$question_id);
        }

        // Check if the selected answer id matches the correct answer id
        $is_correct = ($correctOption->option_value == $selectedOptionId);

        

        if($is_correct){
            // Retrieve student ID from session
            $currentStudent = Yii::$app->session->get('current_student');

            if (!$currentStudent) {
                throw new \Exception('Student details not found in session.');
            }
            $studentId = $currentStudent['id'];

            
            $testAttempt = new LessonTestAttempt(); 
            $testAttempt->lesson_test_id = $lessonContent->lesson_id;
            $testAttempt->student_id = $studentId;
            $testAttempt->question_id = $question_id;
            $testAttempt->score = $lessonContent->points;

     

            // $testAttempt->date = date('Y-m-d');
            $testAttempt->status= 1;
            $testAttempt->save();
            //         print_r($testAttempt->getErrors());
            // die();
            
        }

        // print $correctOption->option_value .' checking '.$selectedOptionId;
        // die();
        
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


public function actionSaveQuiz()
{
    $lesson_id = Yii::$app->request->post('lesson_id');
    $answers = Yii::$app->request->post('answers');

    $totalQuestions = count($answers);
    $correctAnswers = 0;

    // Getting current student from session
    $currentStudent = Yii::$app->session->get('current_student');
    if (!$currentStudent) {
        throw new \Exception('Student details not found in session.');
    }
    $studentId = $currentStudent['id'];

    // Fetching the subject and lesson details
    $lessonContent = LessonContent::find()->where(['lesson_id' => $lesson_id])->one();
    if ($lessonContent) {
        $subject = $lessonContent->subject;
        $subjectName = $subject ? $subject->title : 'Unknown Subject';
    } else {
        $subjectName = 'Unknown Subject';
    }

    $lesson = Lesson::findOne($lesson_id);
    $lessonName = $lesson ? $lesson->title : 'Unknown Lesson';

    foreach ($answers as $question_id => $selectedAnswer) {
        $question = LessonContent::findOne($question_id);
        $correctAnswerModel = TestQuestionAnswer::findOne(['question_id' => $question_id]);
        
        // Checking if the selected answer is correct
        if ($correctAnswerModel !== null) {
            $correctAnswer = $correctAnswerModel->answer;
            if ($correctAnswer === $selectedAnswer) {
                $correctAnswers++;
                
                // Saving the correct attempt to the database
                $testAttempt = new LessonTestAttempt();
                $testAttempt->lesson_test_id = $lesson_id;
                $testAttempt->student_id = $studentId;
                $testAttempt->question_id = $question_id;
                $testAttempt->score = 1; // Correct answer
                $testAttempt->status = 1;
                $testAttempt->created_by = Yii::$app->user->id;
                $testAttempt->updated_by = Yii::$app->user->id;
                $testAttempt->created_at = date('Y-m-d');
                $testAttempt->updated_at = date('Y-m-d');
                $testAttempt->save();
            }
        } else {
            Yii::warning("Correct answer not found for question ID: $question_id");
        }
    }

    // Summing up the total points for the lesson
    $totalMarks = LessonContent::find()
        ->where(['lesson_id' => $lesson_id])
        ->sum('points');

    $points = $correctAnswers;
    // Storing necessary information in session for result page
    Yii::$app->session->set('total_points_' . $lesson_id, $points);
    Yii::$app->session->set('student_id', $studentId);
    Yii::$app->session->set('total_questions_' . $lesson_id, $totalQuestions);
    Yii::$app->session->set('subject_name_' . $lesson_id, $subjectName);
    Yii::$app->session->set('lesson_name_' . $lesson_id, $lessonName);
     
    return $this->redirect(['result-page', 'lesson_id' => $lesson_id]);
}




public function actionResultPage($lesson_id)
{
    // Retrieving student and lesson details from session
    $studentId = Yii::$app->session->get('student_id');
    $subjectName = Yii::$app->session->get('subject_name_' . $lesson_id);
    $lessonName = Yii::$app->session->get('lesson_name_' . $lesson_id);

    // Fetching student details
    $student = Student::findOne($studentId);
    $studentName = $student ? $student->full_name : 'Unknown Student';

    $totalPoints = Yii::$app->session->get('total_points_' . $lesson_id);
    $totalQuestions = Yii::$app->session->get('total_questions_' . $lesson_id);

    // Fetching the latest test score for the student and lesson
    $testScore = LessonTestAttempt::find()
        ->where(['lesson_test_id' => $lesson_id, 'student_id' => $studentId])
        ->orderBy(['created_at' => SORT_DESC])
        ->one();

    if ($testScore !== null) {
        // Calculating total and obtained marks
        $lessonContent = LessonContent::find()->where(['lesson_id' => $lesson_id])->all();
        $totalMarks = array_sum(array_column($lessonContent, 'points'));

        $obtainedMarks = LessonTestAttempt::find()
            ->where(['lesson_test_id' => $lesson_id, 'student_id' => $studentId])
            ->sum('score');

        // Calculating the percentage
        $percentage = ($totalMarks > 0) ? number_format(($obtainedMarks / $totalMarks) * 100, 2) : 0;

        // Rendering the result page with calculated values
        return $this->render('result-page', [
            'testScore' => $testScore,
            'totalMarks' => $totalMarks,
            'obtainedMarks' => $obtainedMarks,
            'percentage' => $percentage,
            'studentName' => $studentName,
            'totalQuestions' => $totalQuestions,
            'subjectName' => $subjectName,
            'lessonName' => $lessonName,
        ]);
    } else {
        throw new NotFoundHttpException('No test score found for the specified lesson and student.');
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

