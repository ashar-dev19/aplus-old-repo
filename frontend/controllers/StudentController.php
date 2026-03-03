<?php



namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use frontend\models\Student;
use frontend\models\search\StudentSearch;
use frontend\models\Subject;
use frontend\models\LessonRead;
use frontend\models\Grade;
use frontend\models\Points;
use frontend\models\search\GradeSearch;
use frontend\models\LessonTestAttempt;


use frontend\models\Lesson;
use frontend\models\Chapter;
use yii\web\Response;

  use yii\db\Query;
use yii\db\Expression;


use backend\modules\student_management\models\Topics;






/**

 * @author Eugene Terentev <eugene@terentev.net>

 */





class StudentController extends Controller

{

    private const POSTS_PER_PAGE = 4;

    // private const ARCHIVE_MONTHS_COUNT = 12;



    /**

     * @return string

     */


  
        private function setCurrentStudentSession(\frontend\models\Student $model): void
        {
            Yii::$app->session->set('current_student', [
                'id'    => $model->id,
                'name'  => $model->full_name,
                'image' => $model->details,
            ]);
        }

        
        public function actionSelectAndReports($id)
        {
            $model = \frontend\models\Student::findOne((int)$id);
            if (!$model) {
                throw new \yii\web\NotFoundHttpException('Student not found.');
            }

            // same permission guard as actionView
            $isPrivileged = Yii::$app->user->can('admin') || Yii::$app->user->can('manageStudents');
            if (!$isPrivileged && (int)$model->parent_id !== (int)Yii::$app->user->id) {
                Yii::$app->session->setFlash('error', 'You are not allowed to open this student.');
                return $this->redirect(['/student/current']);
            }

            // set session
            $this->setCurrentStudentSession($model);

            // go to reports with student_id
            return $this->redirect(['reports/progress-report', 'student_id' => $model->id]);
        }




    public function actionIndex()

    {

        $searchModel = new StudentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort = [
            'defaultOrder' => ['id' => SORT_DESC]
        ];
        // $dataProvider->pagination = ['pageSize' => 4];
        $dataProvider->pagination = [
            'pageSize' => self::POSTS_PER_PAGE
        ];
        
         return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }


    public function actionCurrent()
	{
		$studentSearch = new StudentSearch();
        // $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $queryParams["StudentSearch"]["parent_id"] = Yii::$app->user->id;
          $dataProvider = $studentSearch->search($queryParams);

        $dataProvider->sort = [
            'defaultOrder' => ['id' => SORT_DESC]
        ];
        // $dataProvider->pagination = ['pageSize' => 4];
        $dataProvider->pagination = [
            'pageSize' => 10 //self::POSTS_PER_PAGE
        ];
        
        // $this->layout = '@frontend/views/layouts/_minimal.php';
        $this->layout = '@frontend/views/layouts/_header-before.php';
        
         return $this->render('index', [
            'searchModel' => $studentSearch,
            'dataProvider' => $dataProvider,
         
        ]);

    }

     
	/**

     * @param $slug

     * @return string

     * @throws NotFoundHttpException

     */

	 public function actionCreate()
{
    $model = new Student();

    if ($model->load($this->request->post())) {
         
        $model->parent_id = Yii::$app->user->id;
        $model->status = 1;

        // Check if a student with the same name already exists
        $existingStudent = Student::findOne(['full_name' => $model->full_name]);
        if ($existingStudent !== null) {
            // Render the create view with the existing model
            return $this->render('create', ['model' => $model]);
        }

        // Process file upload if available
        $imageFile = UploadedFile::getInstance($model, 'details');
        if ($imageFile !== null) {
            $imageFile->saveAs('uploads/' . $imageFile->baseName . '.' . $imageFile->extension);
            $model->details = $imageFile->baseName . '.' . $imageFile->extension;
        }

        
        // Save the model only if there is no existing student
        if ($model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
    }

    // Load default values if not submitted or validation failed
    $model->loadDefaultValues();

    // $this->layout = '@frontend/views/layouts/_minimal.php';
    $this->layout = '@frontend/views/layouts/_header-before.php';
    return $this->render('create', ['model' => $model]);
}
    

 




public function actionView($id)
{
    $this->layout = '@frontend/views/layouts/_minimal.php';

    // --- OWNERSHIP / PERMISSION GUARD (added) -----------------------------
    /** @var \frontend\models\Student|null $model */
    $model = Student::findOne((int)$id);
    if (!$model) {
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    // Agar admin/privileged nahi ho to sirf apne bachay dekh sakte ho
    $isPrivileged = Yii::$app->user->can('admin') || Yii::$app->user->can('manageStudents');
    if (!$isPrivileged && (int)$model->parent_id !== (int)Yii::$app->user->id) {
        Yii::$app->session->setFlash('error', 'You are not allowed to view that student.');
        return $this->redirect(['/student/current']);
    }
    // ----------------------------------------------------------------------

    // Save student details in session
    Yii::$app->session->set('current_student', [
        'id'    => $model->id,
        'name'  => $model->full_name,
        'image' => $model->details, // Assuming 'details' holds the image path
        // 'gender' => $model->gender ?? null, // uncomment if you need it in header
    ]);

    // Fetch all subjects
    $subjects = Subject::find()->where(['status' => 1])->all();

    // Save subject titles in session
    $subjectTitles = [];
    foreach ($subjects as $subject) {
        $subjectTitles[] = $subject->title;
    }
    Yii::$app->session->set('subject_titles', $subjectTitles);

  
    // --- timezone setup (week boundaries Toronto ke mutabiq) ---
            // $displayTzId = env('APP_TIMEZONE', 'America/Toronto');
            $displayTzId = Yii::$app->timeZone;   


            $displayTz   = new \DateTimeZone($displayTzId);

            $displayTzId = Yii::$app->timeZone ?: 'America/Toronto';
            $displayTz   = new \DateTimeZone($displayTzId);


            $start = new \DateTime('monday this week 00:00:00', $displayTz);
            $end   = new \DateTime('sunday this week 23:59:59',  $displayTz);

            // DATE column ke liye sirf YYYY-MM-DD chahiye
            $startDate = $start->format('Y-m-d');
            $endDate   = $end->format('Y-m-d');

            // `date` column already DATE hai -> seedha select & between
            $lessonReads = LessonRead::find()
                ->select(['attempt_date' => 'date'])
                ->where(['student_id' => $model->id])
                ->andWhere(['between', 'date', $startDate, $endDate])
                ->groupBy(['date'])
                ->asArray()
                ->all();

            // normalize array of 'YYYY-MM-DD'
            $attemptDates   = array_column($lessonReads, 'attempt_date');
            $weekStartTzStr = $start->format('Y-m-d');




    // // Extract the attempt dates into an array
    // $attemptDates = array_map(function ($read) {
    //     return $read['attempt_date'];
    // }, $lessonReads);

    // Calculate the number of unique days in the current week
    $uniqueDaysAttempted = count($attemptDates);

    // Calculate continuous streak days
    $continuousDaysCount = 0;
    $currentStreak = 0;

    if (!empty($attemptDates)) {
        // Sort the dates in ascending order
        sort($attemptDates);

        $previousDate = null;
        foreach ($attemptDates as $date) {
            if ($previousDate) {
                $dateDiff = (strtotime($date) - strtotime($previousDate)) / (60 * 60 * 24);
                if ($dateDiff == 1) {
                    $currentStreak++;
                } elseif ($dateDiff > 1) {
                    $currentStreak = 1;
                }
            } else {
                $currentStreak = 1;
            }

            if ($currentStreak > $continuousDaysCount) {
                $continuousDaysCount = $currentStreak;
            }

            $previousDate = $date;
        }
    }

    // Add condition if streak is 5 days or more
    if ($continuousDaysCount >= 5) {
        Yii::$app->session->setFlash('success', 'You have maintained a streak for 5 days! Great job!!');
    }

  

// Total points = Earn (is_redempt NULL/0) − Deduct (is_redempt = 1)
$sumExpr = new Expression("COALESCE(SUM(CASE WHEN is_redempt = 1 THEN -points ELSE points END),0)");
$totalPoints = (new Query())
    ->from(Points::tableName())
    ->select($sumExpr)
    ->where(['student_id' => $model->id, 'status' => 1])   // status filter optional but recommended
    ->scalar();
$totalPoints = (int)$totalPoints;



// --- tzdebug info for ?tzdebug=1 ---
$tzDebug = [];
if (Yii::$app->request->get('tzdebug')) {
    $tzDebug = [
        'appTimeZone'        => Yii::$app->timeZone,
        'formatterTimeZone'  => Yii::$app->formatter->timeZone,
        'formatterDefaultTZ' => Yii::$app->formatter->defaultTimeZone,
        'phpDefaultTZ'       => date_default_timezone_get(),
        'serverNow_epoch'    => time(),
        'serverNow_iso'      => date('c'),
    ];
}



    // Render
    return $this->render('view', [
        'model'                => $model,
        'subjects'             => $subjects,
        'attemptDates'         => $attemptDates,
        'uniqueDaysAttempted'  => $uniqueDaysAttempted,
        'continuousDaysCount'  => $continuousDaysCount,
        'totalPoints'          => $totalPoints,
        'weekStartTzStr'      => $weekStartTzStr, 
        'tzDebug'             => $tzDebug, 
    ]);
}



 

// public function actionLessonSuggest($q = '')
// {
//     Yii::$app->response->format = Response::FORMAT_JSON;

//     // clean + normalize spaces
//     $q = trim(preg_replace('/\s+/u', ' ', (string)$q));
//     if ($q === '' || mb_strlen($q) < 2) {
//         return ['items' => []];
//     }

//     // split words: "convert fractions" -> ["convert","fractions"]
//     $words = preg_split('/\s+/u', $q, -1, PREG_SPLIT_NO_EMPTY);

//     $query = Lesson::find()
//         ->select(['id', 'title'])
//         ->where(['status' => 1]);

//     // AND each word with LIKE '%word%'
//     foreach ($words as $w) {
//         $query->andWhere(['like', 'title', $w]);
//     }

//     // optional: prefix boost (if the whole phrase matches from start)
//     // falls back to plain title sort if DB can't use this expression
//     $query->orderBy(['title' => SORT_ASC])->limit(20);

//     $items = $query->asArray()->all();

//     // small fallback: if AND match ne kuch na diya, whole-phrase LIKE se try
//     if (empty($items)) {
//         $items = Lesson::find()
//             ->select(['id', 'title'])
//             ->where(['status' => 1])
//             ->andWhere(['like', 'title', $q])
//             ->orderBy(['title' => SORT_ASC])
//             ->limit(20)
//             ->asArray()
//             ->all();
//     }

//     return ['items' => $items];
// }

 
public function actionLessonSuggest($q = '')
{
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    // normalize
    $q = trim(preg_replace('/\s+/u', ' ', (string)$q));
    if ($q === '' || mb_strlen($q) < 2) {
        return ['items' => []];
    }

    // words = ["perimeter","steps"] etc.
    $words = preg_split('/\s+/u', $q, -1, PREG_SPLIT_NO_EMPTY);

    // Build dynamic relevance expression
    // score = sum over words of (exact*3 + prefix*2 + substr*1)
    $select = ['id', 'title'];
    $params = [];
    $scoreParts = [];

    foreach ($words as $i => $w) {
        $w_lc = mb_strtolower($w);

        // params for this token
        $params[":re_word_$i"]   = "[[:<:]]" . preg_quote($w_lc, '/') . "(s)?[[:>:]]"; // exact word (allow plural s)
        $params[":prefix_$i"]    = $w_lc . '%';
        $params[":substr_$i"]    = '%' . $w_lc . '%';

        // CASE block for this token
        $scoreParts[] =
            "(CASE WHEN LOWER(title) REGEXP :re_word_$i THEN 3 ELSE 0 END
            + CASE WHEN LOWER(title) LIKE :prefix_$i THEN 2 ELSE 0 END
            + CASE WHEN LOWER(title) LIKE :substr_$i THEN 1 ELSE 0 END)";
    }

    $scoreExpr = new \yii\db\Expression(implode(' + ', $scoreParts));
    $select[]  = $scoreExpr . ' AS score';

    // Base query
    $query = \frontend\models\Lesson::find()
        ->select($select)
        ->where(['status' => 1]);

    // Soft filter: har lafz kahin na kahin aa jaye (substring) – taake results wide ho jayein
    foreach ($words as $i => $w) {
        $query->andWhere(['like', 'title', $w]);
    }

    $items = $query
        ->params($params)
        ->orderBy(['score' => SORT_DESC, 'title' => SORT_ASC])
        ->limit(100)  // <= pehle 20 thi; ab zyada dikhao
        ->asArray()
        ->all();

    // Fallback (rare): agar score wali query result na de to plain LIKE (OR) try kar lo
    if (empty($items)) {
        $query = \frontend\models\Lesson::find()
            ->select(['id','title'])
            ->where(['status' => 1]);

        // OR any-word
        $or = ['or'];
        foreach ($words as $w) {
            $or[] = ['like', 'title', $w];
        }
        $items = $query->andWhere($or)
            ->orderBy(['title' => SORT_ASC])
            ->limit(100)
            ->asArray()
            ->all();
    }

    return ['items' => $items];
}


public function actionLessonInfo($id = null, $title = null)
{
    $this->layout = '@frontend/views/layouts/_minimal.php';

    // 1) Agar title diya ho to us title ke sab lessons dikhao (as-is)
    if ($title !== null) {
        $norm = trim($title);
        $lessons = \frontend\models\Lesson::find()->alias('l')
            ->joinWith(['chapter c' => function($q){ $q->joinWith('grade g'); }])
            ->where(['l.status' => 1])
            ->andWhere('LOWER(TRIM(l.title)) = LOWER(TRIM(:t))', [':t' => $norm])
            ->orderBy(['l.subject_id'=>SORT_ASC,'c.grade_id'=>SORT_ASC,'c.title'=>SORT_ASC,'l.id'=>SORT_ASC])
            ->all();

        return $this->render('lesson-info', [
            'rows'        => $this->buildLessonRows($lessons),
            'searchTitle' => $norm
        ]);
    }

    // 2) Autocomplete se id aaye: pehle woh lesson nikalo, phir uske title ke sab clones lao
    if ($id !== null) {
        $one = \frontend\models\Lesson::find()->alias('l')
            ->joinWith(['chapter c' => function($q){ $q->joinWith('grade g'); }])
            ->where(['l.id' => (int)$id])
            ->one();

        if (!$one) {
            throw new \yii\web\NotFoundHttpException('Lesson not found');
        }

        $norm = trim($one->title);

        $lessons = \frontend\models\Lesson::find()->alias('l')
            ->joinWith(['chapter c' => function($q){ $q->joinWith('grade g'); }])
            ->where(['l.status' => 1])
            ->andWhere('LOWER(TRIM(l.title)) = LOWER(TRIM(:t))', [':t' => $norm])
            ->orderBy(['l.subject_id'=>SORT_ASC,'c.grade_id'=>SORT_ASC,'c.title'=>SORT_ASC,'l.id'=>SORT_ASC])
            ->all();

        return $this->render('lesson-info-multi', [
            'rows'        => $this->buildLessonRows($lessons),
            'searchTitle' => $norm
        ]);
    }

    throw new \yii\web\BadRequestHttpException('Missing id or title');
}

/**
 * Helper: rows banaye (lesson, chapter, grade)
 */
private function buildLessonRows($lessons)
{
    $rows = [];
    foreach ($lessons as $l) {
        // grade from lesson->grade_id OR chapter->grade
        $gradeTitle = '—';
        if ($l->grade_id && ($g = \frontend\models\Grade::findOne($l->grade_id))) {
            $gradeTitle = $g->title;
        } elseif ($l->chapter && $l->chapter->grade) {
            $gradeTitle = $l->chapter->grade->title;
        }

        $rows[] = [
            'lesson_id'     => $l->id,
            'lesson_title'  => $l->title,
            'chapter_title' => $l->chapter ? $l->chapter->title : '—',
            'grade_title'   => $gradeTitle,
        ];
    }
    return $rows;
}








public function actionUpdate($id)
{
    $model = $this->findModel($id);
    $oldImage = $model->details;  

    if ($model->load(Yii::$app->request->post())) {
        // Process file upload if a new image is uploaded
        $imageFile = UploadedFile::getInstance($model, 'details');
        if ($imageFile) {
            $filePath = 'uploads/' . $imageFile->baseName . '.' . $imageFile->extension;
            if ($imageFile->saveAs($filePath)) {
                // Assign the new file path to the model
                $model->details = $filePath;

                // Optionally, delete the old image if a new one is uploaded
                if ($oldImage && file_exists($oldImage)) {
                    unlink($oldImage);
                }
            } else {
                Yii::$app->session->setFlash('error', 'Failed to upload image.');
            }
        } else {
            // If no new image is uploaded, retain the old image
            $model->details = $oldImage;
        }

        // Save the model with the updated data
        if ($model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
    }

    $this->layout = '@frontend/views/layouts/_header-before.php';
    return $this->render('update', [
        'model' => $model,
    ]);
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

    

    public function actionMath()
    {
        $searchModel = new StudentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
       // $dataProvider->pagination = ['pageSize' => 4];
 
        $this->layout = '@frontend/views/layouts/_minimal.php';
         return $this->render('math', [

            'searchModel' => $searchModel,

            'dataProvider' => $dataProvider,

            

        ]);

    }



    


  



     

}

