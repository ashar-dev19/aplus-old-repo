<?php

namespace backend\modules\elearning\controllers;

use Yii;
use backend\modules\elearning\models\LessonContent;
use backend\modules\elearning\models\Subject;
use backend\modules\elearning\models\Chapter;
use backend\modules\elearning\models\Lesson;
use backend\modules\elearning\models\TopicIndexQuestionOptions;
use backend\modules\elearning\models\TestQuestionAnswer;
use backend\modules\elearning\models\search\LessonContentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\db\Query;

/**
 * LessonContentController implements the CRUD actions for LessonContent model.
 */
class LessonContentController extends Controller
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
     * Lists all LessonContent models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LessonContentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single LessonContent model.
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
     * Creates a new LessonContent model.
     * Handles both CSV import and manual form creation.
     */
    public function actionCreate()
    {
        $model = new LessonContent();
        $newSubjectModel = new Subject();
        $newChapterModel = new Chapter();
        $newLessonModel = new Lesson();

        $subjects = Subject::find()->all();

        // Handle CSV upload
        if (Yii::$app->request->isPost) {
            $csvFile = UploadedFile::getInstanceByName('csv_file');

            if ($csvFile) {
                $tmpPath = Yii::getAlias('@runtime') . '/upload_' . time() . '_' . $csvFile->name;

                if (!$csvFile->saveAs($tmpPath)) {
                    Yii::$app->session->setFlash('error', 'CSV upload failed.');
                    return $this->redirect(['create']);
                }

                $created = 0;
                $skippedDup = 0;
                $tx = Yii::$app->db->beginTransaction();

                try {
                    $maxQ = (new Query())->from('topic_index_question_options')->max('question_id');
                    $nextQuestionId = (int)$maxQ >= 0 ? ((int)$maxQ + 1) : 1;

                    $defaultSubjectId = (int)(Yii::$app->request->post('Subject')['id'] ?? 0);
                    $defaultChapterId = (int)(Yii::$app->request->post('Chapter')['id'] ?? 0);
                    $defaultLessonId  = (int)(Yii::$app->request->post('Lesson')['id'] ?? 0);

                    if (($h = fopen($tmpPath, 'r')) === false) {
                        throw new \Exception('Unable to open CSV.');
                    }

                    $header = fgetcsv($h);
                    if (!$header) {
                        throw new \Exception('CSV appears empty.');
                    }

                    // Normalize headers
                    $map = [];
                    foreach ($header as $i => $col) {
                        $map[strtolower(trim($col))] = $i;
                    }

                    // Required columns
                    foreach (['question', 'option1', 'option2', 'option3', 'option4', 'correct_answer'] as $req) {
                        if (!array_key_exists($req, $map)) {
                            throw new \Exception("Missing required CSV column: {$req}");
                        }
                    }

                    // Process each row
                    while (($row = fgetcsv($h)) !== false) {
                        $subjectId = isset($map['subject_id']) && $row[$map['subject_id']] !== ''
                            ? (int)$row[$map['subject_id']] : $defaultSubjectId;
                        $chapterId = isset($map['chapter_id']) && $row[$map['chapter_id']] !== ''
                            ? (int)$row[$map['chapter_id']] : $defaultChapterId;
                        $lessonId  = isset($map['lesson_id']) && $row[$map['lesson_id']] !== ''
                            ? (int)$row[$map['lesson_id']] : $defaultLessonId;

                        if (!$subjectId || !$chapterId || !$lessonId) {
                            continue;
                        }

                        $question     = trim((string)$row[$map['question']]);
                        $opt1         = trim((string)$row[$map['option1']]);
                        $opt2         = trim((string)$row[$map['option2']]);
                        $opt3         = trim((string)$row[$map['option3']]);
                        $opt4         = trim((string)$row[$map['option4']]);
                        $correct      = trim((string)$row[$map['correct_answer']]);
                        $explanation  = isset($map['explanation'])
                            ? trim((string)$row[$map['explanation']]) : '';

                        if ($question === '') {
                            continue;
                        }

                        // Duplicate check
                        $dup = LessonContent::find()
                            ->where([
                                'subject_id' => $subjectId,
                                'chapter_id' => $chapterId,
                                'lesson_id'  => $lessonId,
                                'title'      => $question,
                            ])
                            ->exists();

                        if ($dup) {
                            $skippedDup++;
                            continue;
                        }

                        // Insert LessonContent
                        $lc = new LessonContent();
                        $lc->subject_id  = $subjectId;
                        $lc->chapter_id  = $chapterId;
                        $lc->lesson_id   = $lessonId;
                        $lc->title       = $question;
                        $lc->content     = $correct;
                        $lc->points      = 20;
                        $lc->status      = 1;
                        $lc->question_id = $nextQuestionId;

                        if (!$lc->save(false)) {
                            throw new \Exception('Failed to save question: ' . $question);
                        }

                        // Options
                        $opts = array_filter([$opt1, $opt2, $opt3, $opt4], fn($v) => $v !== '');
                        if (empty($opts)) {
                            throw new \Exception('No options provided for: ' . $question);
                        }

                        foreach ($opts as $optText) {
                            $opt = new TopicIndexQuestionOptions();
                            $opt->question_id = $nextQuestionId;
                            $opt->option_value = $optText;

                            if (!$opt->save(false)) {
                                throw new \Exception('Failed saving option for: ' . $question);
                            }
                        }

                        // Correct answer
                        if ($correct !== '') {
                            $ans = new TestQuestionAnswer();
                            $ans->question_id = $nextQuestionId;
                            $ans->answer = $correct;
                            $ans->save(false);
                        }

                        // Explanation
                        if ($explanation !== '') {
                            Yii::$app->db->createCommand()->insert('lesson_content_explanation', [
                                'question_id' => $nextQuestionId,
                                'explanation' => $explanation,
                                'status' => 1,
                            ])->execute();
                        }

                        $nextQuestionId++;
                        $created++;
                    }

                    fclose($h);
                    @unlink($tmpPath);
                    $tx->commit();

                    $msg = "CSV import done. {$created} question(s) created.";
                    if ($skippedDup > 0) {
                        $msg .= " {$skippedDup} duplicate(s) skipped.";
                    }

                    Yii::$app->session->setFlash('success', $msg);
                    return $this->redirect(['index']);

                } catch (\Throwable $e) {
                    if (isset($h) && is_resource($h)) fclose($h);
                    @unlink($tmpPath);
                    $tx->rollBack();
                    Yii::$app->session->setFlash('error', $e->getMessage());
                    return $this->redirect(['create']);
                }
            }

            // Normal form flow
            $transaction = Yii::$app->db->beginTransaction();

            try {
                $subjectId = Yii::$app->request->post('Subject')['id'] ?? null;
                if ($subjectId) {
                    $model->subject_id = $subjectId;
                } elseif ($newSubjectModel->load(Yii::$app->request->post()) && $newSubjectModel->save()) {
                    $model->subject_id = $newSubjectModel->id;
                }

                $chapterId = Yii::$app->request->post('Chapter')['id'] ?? null;
                if ($chapterId) {
                    $model->chapter_id = $chapterId;
                } elseif ($newChapterModel->load(Yii::$app->request->post()) && $newChapterModel->save()) {
                    $model->chapter_id = $newChapterModel->id;
                }

                $lessonId = Yii::$app->request->post('Lesson')['id'] ?? null;
                if ($lessonId) {
                    $model->lesson_id = $lessonId;
                } elseif ($newLessonModel->load(Yii::$app->request->post()) && $newLessonModel->save()) {
                    $model->lesson_id = $newLessonModel->id;
                }

                if ($model->save()) {
                    $transaction->commit();
                    return $this->redirect(['create-lesson-options', 'lesson_content_id' => $model->id]);
                } else {
                    throw new \Exception('Error saving Lesson Content.');
                }

            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('create', compact(
            'model',
            'newSubjectModel',
            'newChapterModel',
            'newLessonModel',
            'subjects'
        ));
    }

    public function actionGetChapters($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $chapters = Chapter::find()->where(['subject_id' => $id])->all();
        $results = [];

        foreach ($chapters as $chapter) {
            $results[] = [
                'id' => $chapter->id,
                'title' => $chapter->title,
            ];
        }

        return $results;
    }

    public function actionGetLessons($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $lessons = Lesson::find()->where(['chapter_id' => $id])->all();
        $results = [];

        foreach ($lessons as $lesson) {
            $results[] = [
                'id' => $lesson->id,
                'title' => $lesson->title,
            ];
        }

        return $results;
    }

    public function actionCreateLessonOptions($lesson_content_id)
    {
        $lessonContent = LessonContent::findOne($lesson_content_id);
        if (!$lessonContent) {
            throw new NotFoundHttpException('Lesson Content not found.');
        }

        $maxQuestionId = (new Query())->select('MAX(question_id)')
            ->from('topic_index_question_options')
            ->scalar();

        $newQuestionId = $maxQuestionId + 1;
        $lessonContent->question_id = $newQuestionId;

        if (!$lessonContent->save()) {
            Yii::$app->session->setFlash('error', 'Error updating Lesson Content with new question_id.');
            return $this->redirect(['view', 'id' => $lesson_content_id]);
        }

        if (Yii::$app->request->post()) {
            $lessonTestOptions = Yii::$app->request->post('LessonTestOption', []);
            $correctAnswer = null;

            foreach ($lessonTestOptions as $index => $option) {
                if (!empty($option['option_value'])) {
                    $optionModel = new TopicIndexQuestionOptions();
                    $optionModel->question_id = $newQuestionId;
                    $optionModel->option_value = $option['option_value'];

                    if (!$optionModel->save()) {
                        Yii::$app->session->setFlash('error', 'Error saving test option: ' . json_encode($optionModel->getErrors()));
                    }

                    if (!empty($option['is_correct'])) {
                        $correctAnswer = $option['option_value'];
                    }
                }
            }

            if ($correctAnswer) {
                $testQuestionAnswer = new TestQuestionAnswer();
                $testQuestionAnswer->question_id = $newQuestionId;
                $testQuestionAnswer->answer = $correctAnswer;

                if (!$testQuestionAnswer->save()) {
                    Yii::$app->session->setFlash('error', 'Error saving correct answer: ' . json_encode($testQuestionAnswer->getErrors()));
                }
            }

            Yii::$app->session->setFlash('success', 'Options saved successfully!');
            return $this->redirect(['view', 'id' => $lesson_content_id]);
        }

        return $this->render('create-lesson-options', compact('lessonContent'));
    }




/**
 * Updates an existing LessonContent model.
 * If update is successful, the browser will be redirected to the 'view' page.
 *
 * @param integer $id
 * @return mixed
 */
public function actionUpdate($id)
{
    ini_set('memory_limit', '128M');

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
 *
 * @param integer $id
 * @return mixed
 */
public function actionDelete($id)
{
    $this->findModel($id)->delete();
    return $this->redirect(['index']);
}

/**
 * Finds the LessonContent model based on its primary key value.
 * If the model is not found, a 404 HTTP exception will be thrown.
 *
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