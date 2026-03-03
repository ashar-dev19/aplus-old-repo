<?php

namespace frontend\controllers;

use Yii;
use frontend\models\LessonContent;
use frontend\models\LessonTest;
use yii\web\Controller;

class QuizController extends Controller
{
    public function actionIndex()
    {
        session_start();
        ini_set('memory_limit', '6281M');

        // Fetch questions from the database
        $questions = LessonContent::find()->where(['status' => 1])->all();
        if (!$questions) {
            throw new \yii\web\NotFoundHttpException('No questions found.');
        }

        $totalQuestions = count($questions);

        if (!isset($_SESSION['currentQuestionIndex'])) {
            $_SESSION['currentQuestionIndex'] = 0;
            $_SESSION['points'] = 0;
            $_SESSION['attempts'] = 0;
            $_SESSION['startTime'] = time();
        }

        if (Yii::$app->request->isPost) {
            if (isset($_POST['next'])) {
                if (isset($_POST['option']) && $_POST['option'] == $questions[$_SESSION['currentQuestionIndex']]->content) {
                    $_SESSION['points'] += $questions[$_SESSION['currentQuestionIndex']]->points;
                }
                $_SESSION['currentQuestionIndex']++;
                $_SESSION['attempts']++;
            } elseif (isset($_POST['previous'])) {
                $_SESSION['currentQuestionIndex']--;
            } elseif (isset($_POST['finish'])) {
                if (isset($_POST['option']) && $_POST['option'] == $questions[$_SESSION['currentQuestionIndex']]->content) {
                    $_SESSION['points'] += $questions[$_SESSION['currentQuestionIndex']]->points;
                }
                $_SESSION['attempts']++;

                $totalPoints = $_SESSION['points'];
                $totalAttempts = $_SESSION['attempts'];
                $startTime = date('Y-m-d H:i:s', $_SESSION['startTime']);
                $endTime = date('Y-m-d H:i:s');

                $lessonTest = new LessonTest();
                $lessonTest->lesson_id = $questions[0]->lesson_id; // Assuming all questions are from the same lesson
                $lessonTest->student_id = Yii::$app->user->id;
                $lessonTest->attempt = $totalAttempts;
                $lessonTest->points = $totalPoints;
                $lessonTest->total_points = $totalQuestions * 10; // Assuming each question is worth 10 points
                $lessonTest->date_started = $startTime;
                $lessonTest->date_completed = $endTime;
                $lessonTest->status = 1;
                $lessonTest->save();

                // Reset session variables for a new attempt
                $_SESSION['currentQuestionIndex'] = 0;
                $_SESSION['points'] = 0;
                $_SESSION['attempts'] = 0;

                return $this->render('result', [
                    'totalPoints' => $totalPoints,
                    'totalAttempts' => $totalAttempts,
                ]);
            }
        }

        $currentIndex = $_SESSION['currentQuestionIndex'];
        $question = $questions[$currentIndex];

        return $this->render('index', [
            'question' => $question,
            'totalQuestions' => $totalQuestions,
            'currentIndex' => $currentIndex,
        ]);
    }
    
}

