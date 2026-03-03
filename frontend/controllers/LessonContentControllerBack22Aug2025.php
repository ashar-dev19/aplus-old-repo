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
use frontend\models\Chapter;
use frontend\models\Student;
use frontend\models\Points;
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
    
        // Retrieve the lesson using the lesson_id
        $lesson = Lesson::findOne($lesson_id);
        
        if ($lesson === null) {
            throw new NotFoundHttpException('The requested lesson does not exist.');
        }
    
        // Store the original referrer if it's not already set
        // if (!Yii::$app->session->has('originalReferrerUrl')) {
        //     Yii::$app->session->set('originalReferrerUrl', Yii::$app->request->referrer);
        // }
    
        // Retrieve student ID from session
        $currentStudent = Yii::$app->session->get('current_student');
        if (!$currentStudent) {
            throw new \Exception('Student details not found in session.');
        }
        $studentId = $currentStudent['id'];
    
        // Update the lesson name in the session
        Yii::$app->session->set('lessonName', $lesson->title);
    
        // Get the lesson name from the session
        $lessonName = Yii::$app->session->get('lessonName', 'Default Lesson Name'); // Default value if not found

          // Track the current question index
        $currentQuestionIndex = Yii::$app->session->get('current_question_index', 1);
        $totalQuestions = count($lessonContent); // Total number of questions

    
        $this->layout = '@frontend/views/layouts/_noheader.php';
    
        // Pass lesson_id and existingAttempt to the view
        return $this->render('tutorial', [
            'lessonContent' => $lessonContent,
            'lesson_id' => $lesson_id,
            'lesson' => $lesson, // Pass the lesson object to the view
            'lessonName' => $lessonName, // Pass the lesson name to the view
            'currentQuestionIndex' => $currentQuestionIndex,
            'totalQuestions' => $totalQuestions,
        ]);
    }
    
    
    

    public function actionTest($lesson_id)
    {
        // Store the current timestamp when the test page is loaded
        Yii::$app->session->set('test_start_time', time());
        
        // Retrieve the lesson using the lesson_id
        $lesson = Lesson::findOne($lesson_id);
        
        if ($lesson === null) {
            throw new NotFoundHttpException('The requested lesson does not exist.');
        }
    
        // Store the original referrer if it's not already set
        if (!Yii::$app->session->has('originalReferrerUrl')) {
            Yii::$app->session->set('originalReferrerUrl', Yii::$app->request->referrer);
        }
    
        // Retrieve student ID from session
        $currentStudent = Yii::$app->session->get('current_student');
        if (!$currentStudent) {
            throw new \Exception('Student details not found in session.');
        }
        $studentId = $currentStudent['id'];
    
        // Mark lesson as read
        $lessonRead = new LessonRead();
        $lessonRead->lesson_id = $lesson_id;
        $lessonRead->student_id = $studentId;
        $lessonRead->date = date('Y-m-d');
        $lessonRead->status = 1;
        $lessonRead->save();
    
        // Update Chapter Progress
        $this->updateChapterProgress($lesson_id, $studentId);

         // Fetching lesson content
        $lessonContent = LessonContent::find()
        ->where(['lesson_id' => $lesson_id])
        ->orderBy('id') // Keep this if you want a consistent order before shuffling
        ->all();

        // Randomize the order of questions
        // shuffle($lessonContent);

    
        // Fetching student's attempts for this lesson (complete attempts only)
        $studentAttempts = LessonTestAttempt::find()
            ->where(['lesson_test_id' => $lesson_id, 'student_id' => $studentId, 'status' => LessonTestAttempt::STATUS_COMPLETE])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();
    
        // Fetch explanations for the lesson content
        $explanations = [];
        foreach ($lessonContent as $content) {
            $explanations[$content->id] = $content->getExplanations()->all();
        }
    
        $attemptCount = count($studentAttempts);
        $totalQuestions = count($lessonContent); 
         
        $currentQuestionId = $lessonContent[0]->id; 
    
        if ($lessonContent !== null) {
            $this->layout = '@frontend/views/layouts/_noheader.php';
    
            return $this->render('test', [
                'lessonContent' => $lessonContent,
                'lesson_id' => $lesson_id,
                'lesson' => $lesson, 
                'studentAttempts' => $studentAttempts,
                'attemptCount' => $attemptCount, 
                'explanations' => $explanations, 
                'totalQuestions' => $totalQuestions,
                'currentQuestionId' => $currentQuestionId,
            ]);
        } else {
            throw new NotFoundHttpException('Lesson content not found for lesson ID: ' . $lesson_id);
        }
    }
    
    
 
    

    



/**
 * Update the progress of a chapter after a lesson test is completed.
 *
 * @param integer $lesson_id The ID of the lesson that was read/tested.
 * @param integer $student_id The ID of the student who attempted the test.
 */
protected function updateChapterProgress($lesson_id, $student_id)
{
    // Get the lesson record to find the related chapter
    $lesson = Lesson::findOne($lesson_id);
    if (!$lesson) {
        return; // Lesson not found, exit the function
    }

    // Get the chapter ID associated with this lesson
    $chapter_id = $lesson->chapter_id;

    // Get the chapter model
    $chapter = Chapter::findOne($chapter_id);
    if (!$chapter) {
        return; // Chapter not found, exit the function
    }

    // Calculate progress - for example, by counting completed lessons
    $totalLessons = Lesson::find()->where(['chapter_id' => $chapter_id])->count();

    // Count completed lessons from the `lesson_read` table
    $completedLessons = LessonRead::find()
        ->joinWith('lesson') // Join with the lesson table to access `chapter_id`
        ->where(['lesson.chapter_id' => $chapter_id])
        ->andWhere(['lesson_read.student_id' => $student_id])
        ->andWhere(['lesson_read.status' => 1]) // Assuming status 1 means completed
        ->groupBy('lesson_read.lesson_id') // Group by lesson_id to count unique lessons
        ->count();

    // Calculate progress as a percentage
    $progress = ($totalLessons > 0) ? ($completedLessons / $totalLessons) * 100 : 0;

    // Ensure progress does not exceed 100%
    $progress = min($progress, 100);

    // Update the chapter's progress field
    $chapter->progress = $progress;
    $chapter->save(false); // Save without validation for simplicity
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

        // Find the lesson content based on question ID
        $lessonContent = LessonContent::findOne(['question_id' => $question_id]);
        if (!$lessonContent) {
            throw new \Exception('Lesson content not found.');
        }

        // Find the correct answer for the question
        $correctAnswerModel = TestQuestionAnswer::findOne(['question_id' => $question_id]);
        if (!$correctAnswerModel) {
            throw new \Exception('Correct answer not found.');
        }

        // Check if the selected answer is correct
        $isCorrect = ($correctAnswerModel->answer === $selectedOptionId);

        // Solution text
        $solutionText = $correctAnswerModel->answer ?? 'No answer provided';

        // Always fetch explanation (even for correct answers)
        // $explanation = 'No explanation available.';
        //     $explanations = $lessonContent->getExplanations()->all();
        //     if (!empty($explanations)) {
        //         $explanation = $explanations[0]->explanation;
        //     }
            

            $showExplanationAnyway = Yii::$app->request->post('showExplanationAnyway', false);

            $explanation = '';
            if (!$isCorrect || $showExplanationAnyway) {
                $explanations = $lessonContent->getExplanations()->all();
                if (!empty($explanations)) {
                    $explanation = $explanations[0]->explanation;
                }
            }




        return [
            'status'      => 'success',
            'is_correct'  => $isCorrect,
            'explanation' => $explanation,
            'solution'    => $solutionText,
        ];

    } catch (\Exception $e) {
        return [
            'status'  => 'error',
            'message' => $e->getMessage(),
        ];
    }
}







public function markLessonAsCompleted($lesson_id, $studentId)
{
    $lessonRead = LessonRead::findOne(['lesson_id' => $lesson_id, 'student_id' => $studentId]);
    
    if ($lessonRead && $lessonRead->status == 0) {
        // Update status to 1 (completed) only if it's currently in progress
        $lessonRead->status = 1;
        $lessonRead->save();
    }
}

     

public function actionSaveQuiz()
{
    $request = Yii::$app->request;

    $lesson_id = $request->post('lesson_id');
    $answers   = $request->post('answers', []);

    $currentStudent = Yii::$app->session->get('current_student');
    if (!$currentStudent) {
        throw new \Exception('Student details not found in session.');
    }
    $studentId = $currentStudent['id'];

    $totalQuestions = count($answers);
    $correctPoints  = 0;
    $totalPossiblePoints = 0;

    $lessonContents = LessonContent::find()
        ->where(['lesson_id' => $lesson_id])
        ->all();

    foreach ($lessonContents as $lc) {
        $totalPossiblePoints += (int)$lc->points;
    }

    foreach ($answers as $questionId => $selectedAnswerId) {
        $correctAnswerRow = TestQuestionAnswer::findOne(['question_id' => $questionId]);
        if ($correctAnswerRow !== null && $correctAnswerRow->answer === $selectedAnswerId) {
            $lc = LessonContent::findOne($questionId);
            if ($lc) {
                $correctPoints += (int)$lc->points;
            }
        }
    }

    $percentage = $totalPossiblePoints > 0
        ? ($correctPoints / $totalPossiblePoints) * 100
        : 0;

    $previousAttempts = LessonTestAttempt::find()
        ->where([
            'lesson_test_id' => $lesson_id,
            'student_id'     => $studentId,
            'status'         => LessonTestAttempt::STATUS_COMPLETE,
        ])
        ->count();

    $attemptNumber = $previousAttempts + 1;

    if ($percentage >= 80) {
        if ($attemptNumber <= 3) {
            $pointsEarned = 100;
        } elseif ($attemptNumber <= 5) {
            $pointsEarned = 50;
        } else {
            $pointsEarned = 10;
        }
    } else {
        $pointsEarned = 0;
    }

    $timeSpent = $request->post('time_spent', 0);

    $attempt = new LessonTestAttempt();
    $attempt->lesson_test_id = $lesson_id;
    $attempt->student_id     = $studentId;
    $attempt->attempt        = $attemptNumber;
    $attempt->score          = $correctPoints;
    $attempt->total_score    = $totalPossiblePoints;
    $attempt->points_earned  = $pointsEarned;
    $attempt->status         = LessonTestAttempt::STATUS_COMPLETE;
    $attempt->time_spent     = $timeSpent;
    $attempt->created_by     = $studentId;
    $attempt->updated_by     = $studentId;
    $attempt->created_at     = time();
    $attempt->updated_at     = time();

    if (!$attempt->save(false)) {
        Yii::error("Failed to save test attempt: " . json_encode($attempt->getErrors()), __METHOD__);
        throw new \Exception('Unable to save quiz attempt.');
    }

   // Safely update or insert points without breaking redirect
try {
    if ($pointsEarned > 0) {
        $existingPoints = Points::findOne(['student_id' => $studentId, 'is_redempt' => null]);

        file_put_contents(Yii::getAlias('@runtime') . '/points_debug.txt', "Points Earned: {$pointsEarned}\n", FILE_APPEND);



        if ($existingPoints) {
            $existingPoints->points += $pointsEarned;
            $existingPoints->details = 'Updated after test of lesson ID: ' . $lesson_id;
            $existingPoints->updated_by = $studentId;
            $existingPoints->save(false);
        } else {
            $pointsRecord = new Points();
            $pointsRecord->points = $pointsEarned;
            $pointsRecord->student_id = $studentId;
            $pointsRecord->details = 'Points earned from test of lesson ID: ' . $lesson_id;
            $pointsRecord->status = 1;
            $pointsRecord->created_by = $studentId;
            $pointsRecord->updated_by = $studentId;
            $pointsRecord->save(false);
        }
    }
} catch (\Throwable $e) {
    file_put_contents(Yii::getAlias('@runtime') . '/points_debug.txt', "Error: " . $e->getMessage() . "\n", FILE_APPEND);
}




    // Store for result page
    Yii::$app->session->set('quiz_percentage_' . $lesson_id, $percentage);
    Yii::$app->session->set('quiz_points_earned_' . $lesson_id, $pointsEarned);

    return $this->redirect(['result-page', 'lesson_id' => $lesson_id]);
}





// public function actionResultPage($lesson_id)
// {
//     $studentId = Yii::$app->session->get('student_id');
//     $subjectName = Yii::$app->session->get('subject_name_' . $lesson_id);
//     $lessonName = Yii::$app->session->get('lesson_name_' . $lesson_id);

//     $student = Student::findOne($studentId);
//     $studentName = $student ? $student->full_name : 'Unknown Student';

//     $latestTestAttempt = LessonTestAttempt::find()
//         ->where(['lesson_test_id' => $lesson_id, 'student_id' => $studentId])
//         ->orderBy(['created_at' => SORT_DESC])
//         ->one();

//     if ($latestTestAttempt !== null) {
//         $totalMarks = LessonContent::find()->where(['lesson_id' => $lesson_id])->sum('points');
//         $obtainedMarks = $latestTestAttempt->score;
//         $percentage = ($totalMarks > 0) ? number_format(($obtainedMarks / $totalMarks) * 100, 2) : 0;
        
//         $this->layout = '@frontend/views/layouts/_minimal.php';

//         return $this->render('result-page', [
//             'totalMarks' => $totalMarks,
//             'obtainedMarks' => $obtainedMarks,
//             'percentage' => $percentage,
//             'studentName' => $studentName,
//             'subjectName' => $subjectName,
//             'lessonName' => $lessonName,
//             'timeSpent' => $latestTestAttempt->time_spent,
//             'lesson_id' => $lesson_id,
//         ]);
//     } else {
//         throw new NotFoundHttpException('No test score found.');
//     }
// }


public function actionResultPage($lesson_id)
{
    $studentId = Yii::$app->session->get('current_student')['id'] ?? null;

    
    if (!$studentId) {
        throw new \Exception('Student not logged in.');
    }

    // 1) Find the most recently created attempt for this student+lesson:
    $latestAttempt = LessonTestAttempt::find()
        ->where([
            'lesson_test_id' => $lesson_id,
            'student_id'     => $studentId,
            'status'         => LessonTestAttempt::STATUS_COMPLETE,
        ])
        ->orderBy(['created_at' => SORT_DESC])
        ->one();

    if (!$latestAttempt) {
        throw new NotFoundHttpException('No test score found.');
    }

    // 2) Compute the displayed values
    $percentage    = $latestAttempt->total_score > 0 
                   ? number_format(($latestAttempt->score / $latestAttempt->total_score) * 100, 2) 
                   : 0;

    $pointsEarned  = $latestAttempt->points_earned;    // Already decided in actionSaveQuiz
    $lessonName    = ''; 
    $subjectName   = '';

    // Fetch lesson + subject names for display (optional)
    $lesson = Lesson::findOne($lesson_id);
    if ($lesson) {
        $lessonName = $lesson->title;
        $subject = $lesson->subject; // assuming Lesson has relation getSubject()
        $subjectName = $subject ? $subject->title : '';
    }

    // Fetch Student name
    $student = Student::findOne($studentId);
    $studentName = $student ? $student->full_name : 'Unknown Student';

    $this->layout = '@frontend/views/layouts/_minimal.php';

    // 3) Render the view, passing everything needed
    return $this->render('result-page', [
        'percentage'       => $percentage,
        'obtainedMarks'    => $pointsEarned,
        'totalMarks'       => $latestAttempt->total_score, // or omit if you only want to show percentage + points
        'studentName'      => $studentName,
        'lessonName'       => $lessonName,
        'subjectName'      => $subjectName,
        'timeSpent'        => $latestAttempt->time_spent,
        'lesson_id'        => $lesson_id,
    ]);
}



    



 // Right Now this is not working

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

