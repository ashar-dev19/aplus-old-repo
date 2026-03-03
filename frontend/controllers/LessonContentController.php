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
use yii\db\Expression;

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
    

      
    // public function actionTutorial($lesson_id, $id = null)
    // {
    //     $lessonContent = LessonContent::find()
    //         ->where(['lesson_id' => $lesson_id])
    //         ->orderBy('id')
    //         ->all();
    
    //     // Retrieve the lesson using the lesson_id
    //     $lesson = Lesson::findOne($lesson_id);
        
    //     if ($lesson === null) {
    //         throw new NotFoundHttpException('The requested lesson does not exist.');
    //     }
    
    //     // Store the original referrer if it's not already set
    //     // if (!Yii::$app->session->has('originalReferrerUrl')) {
    //     //     Yii::$app->session->set('originalReferrerUrl', Yii::$app->request->referrer);
    //     // }
    
    //     // Retrieve student ID from session
    //     $currentStudent = Yii::$app->session->get('current_student');
    //     if (!$currentStudent) {
    //         throw new \Exception('Student details not found in session.');
    //     }
    //     $studentId = $currentStudent['id'];
    
    //     // Update the lesson name in the session
    //     Yii::$app->session->set('lessonName', $lesson->title);
    
    //     // Get the lesson name from the session
    //     $lessonName = Yii::$app->session->get('lessonName', 'Default Lesson Name'); // Default value if not found

    //       // Track the current question index
    //     $currentQuestionIndex = Yii::$app->session->get('current_question_index', 1);
    //     $totalQuestions = count($lessonContent); // Total number of questions

    
    //     $this->layout = '@frontend/views/layouts/_noheader.php';
    
    //     // Pass lesson_id and existingAttempt to the view
    //     return $this->render('tutorial', [
    //         'lessonContent' => $lessonContent,
    //         'lesson_id' => $lesson_id,
    //         'lesson' => $lesson, // Pass the lesson object to the view
    //         'lessonName' => $lessonName, // Pass the lesson name to the view
    //         'currentQuestionIndex' => $currentQuestionIndex,
    //         'totalQuestions' => $totalQuestions,
    //     ]);
    // }

    public function actionTutorial($lesson_id, $id = null)
{
    // Lesson fetch (single row)
    $lesson = Lesson::findOne($lesson_id);
    if ($lesson === null) {
        throw new NotFoundHttpException('The requested lesson does not exist.');
    }

    // Student guard (session me array ya id dono handle)
    $currentStudent = Yii::$app->session->get('current_student');
    if (!$currentStudent) {
        throw new \Exception('Student details not found in session.');
    }
    $studentId = is_array($currentStudent) ? ($currentStudent['id'] ?? null) : $currentStudent;

    // Lesson content + explanations eager-load (N+1 fix)
    $lessonContent = LessonContent::find()
        ->where(['lesson_id' => $lesson_id])
        ->orderBy(['id' => SORT_ASC])
        ->with(['explanations']) // relation getExplanations() ko eager load
        ->all();

    // Session updates (as-is)
    Yii::$app->session->set('lessonName', $lesson->title);
    $lessonName = Yii::$app->session->get('lessonName', 'Default Lesson Name');

    // UI helpers
    $currentQuestionIndex = (int)Yii::$app->session->get('current_question_index', 1);
    $totalQuestions       = count($lessonContent);

    $this->layout = '@frontend/views/layouts/_noheader.php';
    return $this->render('tutorial', [
        'lessonContent'        => $lessonContent,
        'lesson_id'            => $lesson_id,
        'lesson'               => $lesson,
        'lessonName'           => $lessonName,
        'currentQuestionIndex' => $currentQuestionIndex,
        'totalQuestions'       => $totalQuestions,
    ]);
}

    
    
    

    // public function actionTest($lesson_id)
    // {
    //     // Store the current timestamp when the test page is loaded
    //     Yii::$app->session->set('test_start_time', time());
        
    //     // Retrieve the lesson using the lesson_id
    //     $lesson = Lesson::findOne($lesson_id);
        
    //     if ($lesson === null) {
    //         throw new NotFoundHttpException('The requested lesson does not exist.');
    //     }
    
    //     // Store the original referrer if it's not already set
    //     if (!Yii::$app->session->has('originalReferrerUrl')) {
    //         Yii::$app->session->set('originalReferrerUrl', Yii::$app->request->referrer);
    //     }
    
    //     // Retrieve student ID from session
    //     $currentStudent = Yii::$app->session->get('current_student');
    //     if (!$currentStudent) {
    //         throw new \Exception('Student details not found in session.');
    //     }
    //     $studentId = $currentStudent['id'];
    
    //     // Mark lesson as read
    //     $lessonRead = new LessonRead();
    //     $lessonRead->lesson_id = $lesson_id;
    //     $lessonRead->student_id = $studentId;
    //     $lessonRead->date = date('Y-m-d');
    //     $lessonRead->status = 1;
    //     $lessonRead->save();
    
    //     // Update Chapter Progress
    //     $this->updateChapterProgress($lesson_id, $studentId);

    //      // Fetching lesson content
    //     $lessonContent = LessonContent::find()
    //     ->where(['lesson_id' => $lesson_id])
    //     ->orderBy('id') // Keep this if you want a consistent order before shuffling
    //     ->all();

    //     // Randomize the order of questions
    //     // shuffle($lessonContent);

    
    //     // Fetching student's attempts for this lesson (complete attempts only)
    //     $studentAttempts = LessonTestAttempt::find()
    //         ->where(['lesson_test_id' => $lesson_id, 'student_id' => $studentId, 'status' => LessonTestAttempt::STATUS_COMPLETE])
    //         ->orderBy(['created_at' => SORT_DESC])
    //         ->all();
    
    //     // Fetch explanations for the lesson content
    //     $explanations = [];
    //     foreach ($lessonContent as $content) {
    //         $explanations[$content->id] = $content->getExplanations()->all();
    //     }
    
    //     $attemptCount = count($studentAttempts);
    //     $totalQuestions = count($lessonContent); 
         
    //     $currentQuestionId = $lessonContent[0]->id; 
    
    //     if ($lessonContent !== null) {
    //         $this->layout = '@frontend/views/layouts/_noheader.php';
    
    //         return $this->render('test', [
    //             'lessonContent' => $lessonContent,
    //             'lesson_id' => $lesson_id,
    //             'lesson' => $lesson, 
    //             'studentAttempts' => $studentAttempts,
    //             'attemptCount' => $attemptCount, 
    //             'explanations' => $explanations, 
    //             'totalQuestions' => $totalQuestions,
    //             'currentQuestionId' => $currentQuestionId,
    //         ]);
    //     } else {
    //         throw new NotFoundHttpException('Lesson content not found for lesson ID: ' . $lesson_id);
    //     }
    // }



    public function actionTest($lesson_id)
{
    ini_set('memory_limit','256M'); 
    
    // Start time once
    Yii::$app->session->set('test_start_time', time());

    // Lesson
    $lesson = Lesson::findOne($lesson_id);
    if ($lesson === null) {
        throw new NotFoundHttpException('The requested lesson does not exist.');
    }

    // Referrer (as-is)
    if (!Yii::$app->session->has('originalReferrerUrl')) {
        Yii::$app->session->set('originalReferrerUrl', Yii::$app->request->referrer);
    }

    // Student guard
    $currentStudent = Yii::$app->session->get('current_student');
    if (!$currentStudent) {
        throw new \Exception('Student details not found in session.');
    }
    $studentId = is_array($currentStudent) ? ($currentStudent['id'] ?? null) : $currentStudent;

    // Mark lesson as read (same behavior, faster save)
    $lessonRead = new LessonRead();
    $lessonRead->lesson_id  = $lesson_id;
    $lessonRead->student_id = $studentId;
    $lessonRead->date       = date('Y-m-d');
    $lessonRead->status     = 1;
    $lessonRead->save(false);

    $this->maybeAwardStreakNow((int)$studentId);

    // Chapter progress (as-is)
    $this->updateChapterProgress($lesson_id, $studentId);

    // --------------- FAST FETCHES ---------------

    // 1) All lesson content + explanations eager-loaded (N+1 fix)
    // $lessonContent = LessonContent::find()
    //     ->where(['lesson_id' => $lesson_id])
    //     ->orderBy(['id' => SORT_ASC])
    //     ->with(['explanations']) 
    //     ->all();

    $lessonContent = LessonContent::find()
    ->where(['lesson_id' => $lesson_id])
    ->with(['explanations'])
    ->all();

    shuffle($lessonContent);



    // 2) Student attempts for this lesson (minimal columns)
    $studentAttempts = LessonTestAttempt::find()
        ->select(['id','attempt','score','total_score','points_earned','time_spent','created_at','status'])
        ->where([
            'lesson_test_id' => $lesson_id,
            'student_id'     => $studentId,
            'status'         => LessonTestAttempt::STATUS_COMPLETE,
        ])
        ->orderBy(['created_at' => SORT_DESC])
        ->asArray()
        ->all();

    // 3) Explanations map (no extra queries because of with(['explanations']))
    $explanations = [];
    foreach ($lessonContent as $content) {
        // $content->explanations already loaded
        $explanations[$content->id] = $content->explanations;
    }

    // Counts
    $attemptCount     = count($studentAttempts);
    $totalQuestions   = count($lessonContent);
    $currentQuestionId = $totalQuestions ? $lessonContent[0]->id : null;

    if ($totalQuestions === 0) {
        throw new NotFoundHttpException('Lesson content not found for lesson ID: ' . $lesson_id);
    }

    $this->layout = '@frontend/views/layouts/_noheader.php';
    return $this->render('test', [
        'lessonContent'     => $lessonContent,
        'lesson_id'         => $lesson_id,
        'lesson'            => $lesson,
        'studentAttempts'   => $studentAttempts,
        'attemptCount'      => $attemptCount,
        'explanations'      => $explanations,
        'totalQuestions'    => $totalQuestions,
        'currentQuestionId' => $currentQuestionId,
    ]);
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



// Helpers 
private function canonicalizeAnswer(string $v): string
{
    $v = trim($v);
    $v = str_replace('&nbsp;', ' ', $v);
    $v = preg_replace('/<\/?(p|span|font)[^>]*>/i', '', $v);

    if (preg_match('/<img[^>]+src=["\']([^"\']+)["\']/i', $v, $m)) {
        return strtolower(basename(ltrim($m[1], '/')));
    }

    $v = html_entity_decode(strip_tags($v));
    $v = preg_replace('/\s+/', ' ', $v);
    return strtolower(trim($v));
}

// ONLY for lessons jahan same image file multiple sizes me aati hai
private function canonicalizeAnswerWithImgSize(string $v): string
{
    $v = html_entity_decode($v, ENT_QUOTES | ENT_HTML5);
    $v = trim(str_replace('&nbsp;', ' ', $v));

    if (preg_match('/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/i', $v, $m)) {
        $src = strtolower(basename(ltrim($m[1], '/')));

        // extract width/height attributes if present
        $w = null; $h = null;
        if (preg_match('/\bwidth=["\']?(\d+)/i', $m[0], $mw))  { $w = (int)$mw[1]; }
        if (preg_match('/\bheight=["\']?(\d+)/i', $m[0], $mh)) { $h = (int)$mh[1]; }

        // include size only if we found something — else fall back to filename
        if ($w || $h) return "{$src}@{$w}x{$h}";
        return $src;
    }

    // fallback to normal text
    $text = strip_tags($v);
    $text = preg_replace('/\s+/', ' ', $text);
    return strtolower(trim($text));
}


/**
 * Improved canonicalization for lessons with repeated images.
 * Builds a canonical string like "imagefile.gif-9" when multiple identical <img> tags appear.
 */
private function canonicalizeAnswerImproved(string $v): string {
    $v = trim(str_replace('&nbsp;', ' ', $v));

    // When the answer consists of multiple <img> tags, collapse them into a single canonical token.
    if (preg_match_all('/<img[^>]+src=["\']([^"\']+)["\']/', $v, $matches)) {
        // Extract file name and count.
        $images = array_map(function ($src) {
            return strtolower(basename(trim($src, '/')));
        }, $matches[1]);

        return $images[0] . '-' . count($images);
    }

    // Fallback for text answers – return a lowercase, whitespace‑collapsed string.
    $text = html_entity_decode(strip_tags($v));
    $text = preg_replace('/\s+/', ' ', $text);
    return strtolower(trim($text));
}

 
private function canonicalizeAnswerMultiImg(string $v, bool $withSize = false): string
{
    $raw = html_entity_decode($v, ENT_QUOTES | ENT_HTML5);
    $raw = trim(str_replace('&nbsp;', ' ', $raw));

    if (preg_match_all('/<img[^>]+>/i', $raw, $tags)) {
        $tokens = [];
        foreach ($tags[0] as $tag) {
            if (!preg_match('/src=["\']([^"\']+)["\']/i', $tag, $m)) continue;
            $src = strtolower(basename(ltrim($m[1], '/')));

            if ($withSize) {
                $w = null; $h = null;
                if (preg_match('/\bwidth=["\']?(\d+)/i',  $tag, $mw)) { $w = (int)$mw[1]; }
                if (preg_match('/\bheight=["\']?(\d+)/i', $tag, $mh)) { $h = (int)$mh[1]; }
                $tokens[] = ($w || $h) ? "{$src}@{$w}x{$h}" : $src;
            } else {
                $tokens[] = $src;
            }
        }
        if (!empty($tokens)) {
            return implode('+', $tokens); // order preserve
        }
    }

    // fallback: text
    $text = strip_tags($raw);
    $text = preg_replace('/\s+/', ' ', $text);
    return strtolower(trim($text));
}

 

private function normalizeHighlightAnswer(string $html): string
{
    $h = (string)$html; 

    // $h = html_entity_decode((string)$html, ENT_QUOTES | ENT_HTML5);
     
    $h = preg_replace(
        '/<span[^>]*?(background\s*:\s*yellow|mso-highlight\s*:\s*yellow)[^>]*>(.*?)<\/span>/is',
        '[[HL]]$2[[/HL]]',
        $h
    );

    // general clean
    $h = str_replace('&nbsp;', ' ', $h);
    $h = preg_replace('/<\/?(p|span|font)[^>]*>/i', '', $h);
    $h = preg_replace('/<button\b([^>]*)>/i', '<div $1>', $h);
    $h = preg_replace('/<\/button>/i', '</div>', $h);
    $h = preg_replace('/\s+/', ' ', $h);
    $h = trim($h);

    // case-insensitive compare ke liye
    return strtolower($h);
}



 
public function actionCheckAnswer()
{
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    $question_id      = Yii::$app->request->post('question_id');
    $selectedOptionId = Yii::$app->request->post('selectedAnswer');

    try {
        if (!$question_id || !$selectedOptionId) {
            throw new \Exception('Required parameters are missing.');
        }

        $lessonContent = LessonContent::findOne(['question_id' => $question_id]);
        if (!$lessonContent) {
            throw new \Exception('Lesson content not found.');
        }

        $correct = TestQuestionAnswer::findOne(['question_id' => $question_id]);
        if (!$correct) {
            throw new \Exception('Correct answer not found.');
        }

                $lessonContent = LessonContent::findOne(['question_id' => $question_id]);
        $lessonId = (int)($lessonContent?->lesson_id);

         
        $PRESERVE_YELLOW = [1311, 1313];

        if (in_array($lessonId, $PRESERVE_YELLOW, true)) {
            
            $normSelected = $this->normalizeHighlightAnswer((string)$selectedOptionId);
            $normCorrect  = $this->normalizeHighlightAnswer((string)$correct->answer);
        } else {
             
            $LESSONS_REQUIRE_SIZE = [45,46,48,49,50,51];

            $withSize     = in_array($lessonId, $LESSONS_REQUIRE_SIZE, true);
            $normSelected = $this->canonicalizeAnswerMultiImg((string)$selectedOptionId, $withSize);
            $normCorrect  = $this->canonicalizeAnswerMultiImg((string)$correct->answer,    $withSize);
        }

        $isCorrect = ($normSelected === $normCorrect);




        $solutionText = $correct->answer ?? 'No answer provided';

        $showExplanationAnyway = Yii::$app->request->post('showExplanationAnyway', false);
        $explanation = '';
        if (!$isCorrect || $showExplanationAnyway) {
            $exps = $lessonContent->getExplanations()->all();
            if (!empty($exps)) {
                $explanation = $exps[0]->explanation;
            }
        }


     
    $isCorrect = ($normSelected === $normCorrect);

        return [
            'status'      => 'success',
            'is_correct'  => $isCorrect,
            'explanation' => $explanation,
            'solution'    => $solutionText,
        ];
    } catch (\Exception $e) {
        return ['status' => 'error', 'message' => $e->getMessage()];
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
    $isAjax  = $request->isAjax;
    if ($isAjax) {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    }

    try {
        $lesson_id = $request->post('lesson_id');
        $answers   = (array)$request->post('answers', []);

        // --- session guard
        $currentStudent = Yii::$app->session->get('current_student');
        if (!$currentStudent) {
            if ($isAjax) return ['ok'=>false,'stage'=>'guard','msg'=>'current_student missing in session'];
            throw new \Exception('Student details not found in session.');
        }
        $studentId = $currentStudent['id'];

        if (!$lesson_id) {
            if ($isAjax) return ['ok'=>false,'stage'=>'guard','msg'=>'lesson_id missing'];
            throw new \Exception('lesson_id missing');
        }

        // ---------- SCORING ----------
        $correctPoints       = 0;
        $totalPossiblePoints = 0;

        $lessonContents = LessonContent::find()->where(['lesson_id' => $lesson_id])->all();
        foreach ($lessonContents as $lc) {
            $totalPossiblePoints += (int)$lc->points;
        }

         

        foreach ($answers as $questionId => $selectedAnswerId) {
            $correctAnswerRow = TestQuestionAnswer::findOne(['question_id' => $questionId]);

                    // per question lesson id
                $lc = LessonContent::findOne(['question_id' => $questionId]);
                $lessonId = (int)($lc?->lesson_id);

                $PRESERVE_YELLOW = [1311, 1313];

                if (in_array($lessonId, $PRESERVE_YELLOW, true)) {
                    // 1311 / 1313 ke liye highlight pattern compare
                    $normSelected = $this->normalizeHighlightAnswer((string)$selectedAnswerId);
                    $normCorrect  = $this->normalizeHighlightAnswer((string)$correctAnswerRow->answer);
                } else {
                    // baaki sab lessons – tumhara purana image/text logic
                    $LESSONS_REQUIRE_SIZE = [45,46,48,49,50,51];
                    $withSize = in_array($lessonId, $LESSONS_REQUIRE_SIZE, true);

                    $normSelected = $this->canonicalizeAnswerMultiImg((string)$selectedAnswerId, $withSize);
                    $normCorrect  = $this->canonicalizeAnswerMultiImg((string)$correctAnswerRow->answer, $withSize);
                }

                if ($normSelected === $normCorrect) {
                    if ($lc) { $correctPoints += (int)$lc->points; }
                }

        }




        $percentage = $totalPossiblePoints > 0 ? ($correctPoints / $totalPossiblePoints) * 100 : 0;

        $previousAttempts = LessonTestAttempt::find()
            ->where([
                'lesson_test_id' => $lesson_id,
                'student_id'     => $studentId,
                'status'         => LessonTestAttempt::STATUS_COMPLETE,
            ])->count();

        $attemptNumber = $previousAttempts + 1;

        if ($percentage >= 80) {
            $pointsEarned = ($attemptNumber <= 3) ? 100 : (($attemptNumber <= 5) ? 50 : 10);
        } else {
            $pointsEarned = 0;
        }

        $timeSpent = (int)$request->post('time_spent', 0);

      
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

      
        $ltaSchema = Yii::$app->db->schema->getTableSchema('lesson_test_attempt', true);
        if ($ltaSchema && isset($ltaSchema->columns['question_id'])) {
            $col = $ltaSchema->columns['question_id'];
            if (!$col->allowNull) {
                $firstQid = !empty($lessonContents) ? (int)$lessonContents[0]->question_id : 0;
                $attempt->question_id = $firstQid ?: 0;
            } 
        }

        
        if ($ltaSchema && isset($ltaSchema->columns['created_at'])) {
            $attempt->created_at = ($ltaSchema->columns['created_at']->type === 'integer')
                ? time()
                : new \yii\db\Expression('NOW()');
        } else {
            $attempt->created_at = time();
        }

        if ($ltaSchema && isset($ltaSchema->columns['updated_at'])) {
            $attempt->updated_at = ($ltaSchema->columns['updated_at']->type === 'integer')
                ? time()
                : new \yii\db\Expression('NOW()');
        } else {
            $attempt->updated_at = time();
        }

        
        try {
            $saved = $attempt->save(false);
        } catch (\Throwable $e) {
            if ($isAjax) {
                return [
                    'ok'         => false,
                    'stage'      => 'attempt_save_exception',
                    'dbMessage'  => $e->getMessage(),
                    'attributes' => $attempt->attributes,
                    'columns'    => $ltaSchema ? array_keys($ltaSchema->columns) : null,
                ];
            }
            throw $e;
        }

        if ($saved) {
            $this->maybeAwardStreakNow((int)$studentId);
        }

        if (!$saved) {
            if ($isAjax) {
                return [
                    'ok'           => false,
                    'stage'        => 'attempt_save_false',
                    'model_errors' => $attempt->getErrors(),
                    'attributes'   => $attempt->attributes,
                    'columns'      => $ltaSchema ? array_keys($ltaSchema->columns) : null,
                ];
            }
            throw new \Exception('Unable to save quiz attempt.');
        }

        // ---------- POINTS (safe) ----------
        try {
            if ($pointsEarned > 0) {
                $pSchema = Yii::$app->db->schema->getTableSchema('points', true);
                $existingPoints = Points::findOne(['student_id' => $studentId, 'is_redempt' => null]);
                if ($existingPoints) {
                    $existingPoints->points    += $pointsEarned;
                    $existingPoints->details    = 'Updated after test of lesson ID: ' . $lesson_id;
                    $existingPoints->updated_by = $studentId;
                    if ($pSchema && isset($pSchema->columns['updated_at'])) {
                        $existingPoints->updated_at = ($pSchema->columns['updated_at']->type === 'integer')
                            ? time()
                            : new \yii\db\Expression('NOW()');
                    }
                    $existingPoints->save(false);
                } else {
                    $p = new Points();
                    $p->points      = $pointsEarned;
                    $p->student_id  = $studentId;
                    $p->details     = 'Points earned from test of lesson ID: ' . $lesson_id;
                    $p->status      = 1;
                    $p->created_by  = $studentId;
                    $p->updated_by  = $studentId;
                    if ($pSchema && isset($pSchema->columns['created_at'])) {
                        $p->created_at = ($pSchema->columns['created_at']->type === 'integer')
                            ? time()
                            : new \yii\db\Expression('NOW()');
                    }
                    if ($pSchema && isset($pSchema->columns['updated_at'])) {
                        $p->updated_at = ($pSchema->columns['updated_at']->type === 'integer')
                            ? time()
                            : new \yii\db\Expression('NOW()');
                    }
                    $p->save(false);
                }
            }
        } catch (\Throwable $pe) {
            if ($isAjax) {
                return ['ok'=>false,'stage'=>'points_exception','dbMessage'=>$pe->getMessage()];
            }
        }

        // ---------- session + redirect ----------
        Yii::$app->session->set('quiz_percentage_' . $lesson_id, $percentage);
        Yii::$app->session->set('quiz_points_earned_' . $lesson_id, $pointsEarned);

        $redirectUrl = \yii\helpers\Url::to(['lesson-content/result-page', 'lesson_id' => $lesson_id]);

        if ($isAjax) {
            return ['ok' => true, 'redirectUrl' => $redirectUrl];
        }

        return $this->redirect($redirectUrl);

    } catch (\Throwable $e) {
        if ($isAjax) {
            return ['ok'=>false,'stage'=>'exception','msg'=>$e->getMessage()];
        }
        throw $e;
    }
}

 

private function appTimeZone(): \DateTimeZone
{
 
 // single source of truth = app->timeZone
  $tzId = Yii::$app->timeZone ?: 'UTC';
    try { return new \DateTimeZone($tzId); } catch (\Throwable $e) { return new \DateTimeZone('UTC'); }
}

/**
 * Global: current week (Mon–Sun, app TZ) me consecutive activity-days >= $requiredDays
 * ho tu ek hi dafa +500 award. Duplicate guard per week.
 */
private function awardStreakRollingBonus(int $studentId, int $requiredDays = 5, int $lookbackDays = 90): void
{
    if ($studentId <= 0 || $requiredDays <= 0) return;

    $tz  = $this->appTimeZone();
    $now = new \DateTime('now', $tz);

    // lookback window
    $from = (clone $now)->modify("-{$lookbackDays} days")->setTime(0,0,0);
    $to   = (clone $now)->setTime(23,59,59);

    $fromStr = $from->format('Y-m-d H:i:s');
    $toStr   = $to->format('Y-m-d H:i:s');
    $fromTs  = $from->getTimestamp();
    $toTs    = $to->getTimestamp();

    // --- collect unique activity dates (LessonRead + LessonTestAttempt)
    $dates = [];

    // LessonRead.date (DATE/DATETIME)
    $reads = \frontend\models\LessonRead::find()
        ->select(new \yii\db\Expression('DATE(date) as d'))
        ->where(['student_id' => $studentId])
        ->andWhere(['between', 'date', $fromStr, $toStr])
        ->groupBy(new \yii\db\Expression('DATE(date)'))
        ->asArray()->all();
    foreach ($reads as $r) { if (!empty($r['d'])) $dates[$r['d']] = true; }

    // LessonTestAttempt.created_at (INT or DATETIME)
    $ltaSchema = Yii::$app->db->schema->getTableSchema(\frontend\models\LessonTestAttempt::tableName(), true);
    if ($ltaSchema && isset($ltaSchema->columns['created_at'])) {
        $col = $ltaSchema->columns['created_at'];
        if ($col->type === 'integer') {
            $attempts = \frontend\models\LessonTestAttempt::find()
                ->select(['created_at'])
                ->where(['student_id' => $studentId, 'status' => \frontend\models\LessonTestAttempt::STATUS_COMPLETE])
                ->andWhere(['between', 'created_at', $fromTs, $toTs])
                ->asArray()->all();
            foreach ($attempts as $a) {
                $d = (new \DateTime('@'.$a['created_at']))->setTimezone($tz)->format('Y-m-d');
                $dates[$d] = true;
            }
        } else {
            $attempts = \frontend\models\LessonTestAttempt::find()
                ->select(new \yii\db\Expression('DATE(created_at) as d'))
                ->where(['student_id' => $studentId, 'status' => \frontend\models\LessonTestAttempt::STATUS_COMPLETE])
                ->andWhere(['between', 'created_at', $fromStr, $toStr])
                ->groupBy(new \yii\db\Expression('DATE(created_at)'))
                ->asArray()->all();
            foreach ($attempts as $a) { if (!empty($a['d'])) $dates[$a['d']] = true; }
        }
    }

    if (empty($dates)) return;

    // --- scan for consecutive run; award ONCE per unbroken run when it first reaches N
    $days = array_keys($dates);
    sort($days);

    $run = 0; $prev = null; $awardedInThisRun = false;

    foreach ($days as $d) {
        if ($prev === null) {
            $run = 1; $awardedInThisRun = false;
        } else {
            $gap = (strtotime($d) - strtotime($prev)) / 86400;
            if ($gap == 1) {
                $run++;
            } else {
                // run broke; reset
                $run = 1;
                $awardedInThisRun = false;
            }
        }
        $prev = $d;

        if ($run >= $requiredDays && !$awardedInThisRun) {
            // is streak ki first N-day window (end = $d)
            $start = (new \DateTime($d, $tz))->modify('-'.($requiredDays-1).' days')->format('Y-m-d');
            $end   = $d;

            // duplicate guard for EXACT same window
            $detail = "STREAK_BONUS_RANGE {$start}..{$end} req={$requiredDays}";
            $exists = \frontend\models\Points::find()
                ->where([
                    'student_id' => $studentId,
                    'status'     => 1,
                    'is_redempt' => null,
                    'details'    => $detail,
                ])->exists();

            if (!$exists) {
                $p = new \frontend\models\Points();
                $p->student_id = $studentId;
                $p->points     = 500;
                $p->details    = $detail;
                $p->status     = 1;
                $p->is_redempt = null;
                $p->created_by = $studentId;
                $p->updated_by = $studentId;

                $ptsSchema = Yii::$app->db->schema->getTableSchema($p::tableName(), true);
                if ($ptsSchema) {
                    if (isset($ptsSchema->columns['created_at'])) {
                        $p->created_at = ($ptsSchema->columns['created_at']->type === 'integer') ? time() : new \yii\db\Expression('NOW()');
                    }
                    if (isset($ptsSchema->columns['updated_at'])) {
                        $p->updated_at = ($ptsSchema->columns['updated_at']->type === 'integer') ? time() : new \yii\db\Expression('NOW()');
                    }
                }

                $p->save(false);
            }

            // same continuous run me dobara award na ho
            $awardedInThisRun = true;
        }
    }
}


 

private function maybeAwardStreakNow(int $studentId): void
{
    $this->awardStreakRollingBonus($studentId, 5);
}



// =============================
// 🔐 Simple secret to protect the URL
private const BACKFILL_TOKEN = 'PUT_YOUR_SECRET_HERE';

// ⛏️ Backfill (all students or single student) — hit via URL
// Examples:
// /lesson-content/streak-backfill?token=PUT_YOUR_SECRET_HERE              (all students, do award)
// /lesson-content/streak-backfill?token=PUT_YOUR_SECRET_HERE&student_id=13286
// /lesson-content/streak-backfill?token=PUT_YOUR_SECRET_HERE&student_id=13286&dry_run=1
// /lesson-content/streak-backfill?token=PUT_YOUR_SECRET_HERE&days=120&req=5
public function actionStreakBackfill($token, $student_id = null, $days = 120, $req = 5, $dry_run = 0)
{
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    if ($token !== self::BACKFILL_TOKEN) {
        return ['ok' => false, 'error' => 'invalid token'];
    }

    $ids = [];
    if ($student_id) {
        $ids = [(int)$student_id];
    } else {
        // jin students ne kabhi activity ki ho (read/attempt) unka union
        $ids1 = (new \yii\db\Query())->select('DISTINCT student_id')->from(\frontend\models\LessonRead::tableName())->column();
        $ids2 = (new \yii\db\Query())->select('DISTINCT student_id')->from(\frontend\models\LessonTestAttempt::tableName())->column();
        $ids  = array_values(array_unique(array_merge($ids1, $ids2)));
    }

    $inserted = 0; $scanned = 0; $preview = [];
    foreach ($ids as $sid) {
        [$made, $windows] = $this->scanAndMaybeAwardStreak($sid, (int)$req, (int)$days, (bool)$dry_run);
        $inserted += $made;
        $scanned++;
        if ($dry_run) { $preview[$sid] = $windows; } // only show windows in dry_run
    }

    return [
        'ok'               => true,
        'dry_run'          => (bool)$dry_run,
        'students_scanned' => $scanned,
        'rows_awarded'     => $inserted,
        'preview'          => $dry_run ? $preview : null,
    ];
}
 
public function actionStreakAudit($token, $student_id)
{
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    if ($token !== self::BACKFILL_TOKEN) {
        return ['ok' => false, 'error' => 'invalid token'];
    }

    $rows = \frontend\models\Points::find()
        ->select(['id','points','details','created_at'])
        ->where([
            'student_id' => (int)$student_id,
            'status'     => 1,
            'is_redempt' => null,
        ])
        ->andWhere(['like', 'details', 'STREAK_BONUS_RANGE %', false])
        ->orderBy(['id' => SORT_DESC])
        ->asArray()->all();

    // int/datetime dono types handle
    foreach ($rows as &$r) {
        $r['created_at_human'] = is_numeric($r['created_at'])
            ? date('Y-m-d H:i:s', (int)$r['created_at'])
            : (string)$r['created_at'];
    }

    return ['ok' => true, 'count' => count($rows), 'rows' => $rows];
}

/**
 * ⚙️ Scan + (optionally) award.
 * dryRun=true ho to sirf windows return hoti hain, DB insert nahi hota.
 * Returns: [insertedCount, windows[]]
 */
private function scanAndMaybeAwardStreak(int $studentId, int $requiredDays, int $lookbackDays, bool $dryRun): array
{
    if ($studentId <= 0 || $requiredDays <= 0) return [0, []];

    $tz  = $this->appTimeZone(); // already defined above in this controller
    $now = new \DateTime('now', $tz);

    $from = (clone $now)->modify("-{$lookbackDays} days")->setTime(0,0,0);
    $to   = (clone $now)->setTime(23,59,59);

    $fromStr = $from->format('Y-m-d H:i:s');
    $toStr   = $to->format('Y-m-d H:i:s');
    $fromTs  = $from->getTimestamp();
    $toTs    = $to->getTimestamp();

    // collect unique activity dates (LessonRead + LessonTestAttempt)
    $dates = [];

    $reads = \frontend\models\LessonRead::find()
        ->select(new \yii\db\Expression('DATE(date) as d'))
        ->where(['student_id' => $studentId])
        ->andWhere(['between', 'date', $fromStr, $toStr])
        ->groupBy(new \yii\db\Expression('DATE(date)'))
        ->asArray()->all();
    foreach ($reads as $r) if (!empty($r['d'])) $dates[$r['d']] = true;

    $ltaSchema = Yii::$app->db->schema->getTableSchema(\frontend\models\LessonTestAttempt::tableName(), true);
    if ($ltaSchema && isset($ltaSchema->columns['created_at'])) {
        $col = $ltaSchema->columns['created_at'];
        if ($col->type === 'integer') {
            $attempts = \frontend\models\LessonTestAttempt::find()
                ->select(['created_at'])
                ->where(['student_id' => $studentId, 'status' => \frontend\models\LessonTestAttempt::STATUS_COMPLETE])
                ->andWhere(['between', 'created_at', $fromTs, $toTs])
                ->asArray()->all();
            foreach ($attempts as $a) {
                $d = (new \DateTime('@'.$a['created_at']))->setTimezone($tz)->format('Y-m-d');
                $dates[$d] = true;
            }
        } else {
            $attempts = \frontend\models\LessonTestAttempt::find()
                ->select(new \yii\db\Expression('DATE(created_at) as d'))
                ->where(['student_id' => $studentId, 'status' => \frontend\models\LessonTestAttempt::STATUS_COMPLETE])
                ->andWhere(['between', 'created_at', $fromStr, $toStr])
                ->groupBy(new \yii\db\Expression('DATE(created_at)'))
                ->asArray()->all();
            foreach ($attempts as $a) if (!empty($a['d'])) $dates[$a['d']] = true;
        }
    }
    if (empty($dates)) return [0, []];

    $days = array_keys($dates);
    sort($days);

    $run = 0; $prev = null; $awardedInThisRun = false;
    $inserted = 0;
    $windows  = [];

    foreach ($days as $d) {
        if ($prev === null) { $run = 1; $awardedInThisRun = false; }
        else {
            $gap = (strtotime($d) - strtotime($prev)) / 86400;
            if ($gap == 1) $run++; else { $run = 1; $awardedInThisRun = false; }
        }
        $prev = $d;

        if ($run >= $requiredDays && !$awardedInThisRun) {
            $start  = (new \DateTime($d, $tz))->modify('-'.($requiredDays-1).' days')->format('Y-m-d');
            $end    = $d;
            $detail = "STREAK_BONUS_RANGE {$start}..{$end} req={$requiredDays}";
            $windows[] = $detail;

            // duplicate guard (same as runtime)
            $exists = \frontend\models\Points::find()->where([
                'student_id' => $studentId,
                'status'     => 1,
                'is_redempt' => null,
                'details'    => $detail,
            ])->exists();

            if (!$exists && !$dryRun) {
                $p = new \frontend\models\Points();
                $p->student_id = $studentId;
                $p->points     = 500;
                $p->details    = $detail;
                $p->status     = 1;
                $p->is_redempt = null;
                $p->created_by = $studentId;
                $p->updated_by = $studentId;

                $ptsSchema = Yii::$app->db->schema->getTableSchema($p::tableName(), true);
                if ($ptsSchema) {
                    if (isset($ptsSchema->columns['created_at'])) {
                        $p->created_at = ($ptsSchema->columns['created_at']->type === 'integer') ? time() : new \yii\db\Expression('NOW()');
                    }
                    if (isset($ptsSchema->columns['updated_at'])) {
                        $p->updated_at = ($ptsSchema->columns['updated_at']->type === 'integer') ? time() : new \yii\db\Expression('NOW()');
                    }
                }
                $p->save(false);
                $inserted++;
            }

            $awardedInThisRun = true; // same continuous run me dobara na ho
        }
    }

    return [$inserted, $windows];
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

